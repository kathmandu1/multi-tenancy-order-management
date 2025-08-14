<?php

namespace Modules\OrderManagement\Services\Order;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\OrderManagement\Contracts\Order\OrderTrackable;
use Modules\OrderManagement\DTO\OrderTrackingDTO;
use Modules\OrderManagement\Enums\OrderTrackingEnum;
use Modules\OrderManagement\Exceptions\InvalidOrderTrackingStatusException;
use Modules\OrderManagement\Models\Order;
use Modules\OrderManagement\Models\OrderTracking;

class OrderTrackingService
{
    public function __construct(

        public  OrderTrackable $orderTrackable
    ) {}

    public function getAll($request, $eagerLoadWithRelationData = []): Collection|LengthAwarePaginator
    {

        try {
            $pagination = false;
            $paginationNumber = null;
            if ($request->has('pagination') && $request->pagination == true) {
                $pagination = $request->pagination;
                $paginationNumber = 10;
            }
            return $this->orderTrackable
                ->getAll($pagination, null, null, $paginationNumber, $eagerLoadWithRelationData);
        } catch (Exception $exception) {
            throw  new Exception($exception);
        }
    }

    public function store(OrderTrackingDTO $orderTrackingDTO): OrderTracking
    {
        try {
            DB::beginTransaction();

            $data = [
                'order_id'        => $orderTrackingDTO->order_id,
                'order_action_by' => $orderTrackingDTO->order_action_by,
                'date'            => $orderTrackingDTO->date,
                'order_status'    => $orderTrackingDTO->order_status,
                'remarks'         => $orderTrackingDTO->remarks
            ];

            if (!$this->canAddTrackingStatus($orderTrackingDTO->order_status, $orderTrackingDTO->order_id)) {
                throw new InvalidOrderTrackingStatusException();
            }
            $orderTracking = $this->orderTrackable->create($data);

            DB::commit();
            return $orderTracking;
        } catch (InvalidOrderTrackingStatusException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function findById(int $id): OrderTracking
    {
        try {

            $modelData = $this->orderTrackable->getById($id);
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
        return $modelData;
    }

    public function update(OrderTrackingDTO $orderTrackingDTO, $id)
    {
        try {
            DB::beginTransaction();
            $data = [
                'order_id' => $orderTrackingDTO->order_id,
                'order_action_by' => $orderTrackingDTO->order_action_by,
                'date' => $orderTrackingDTO->date,
                'order_status' => $orderTrackingDTO->order_status,
                'remarks' => $orderTrackingDTO->remarks
            ];
            $modelData = $this->orderTrackable->update($id, $data);
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception);
        }
        DB::commit();
        return $modelData;
    }

    public function canAddTrackingStatus($newStatus, $orderId): bool|array
    {
        $latestStatus = $this->orderTrackable->getLatestStatusOfOrder($orderId);

        if ($latestStatus === null) {
            return true;
        }

        $newStatus = OrderTrackingEnum::from($newStatus);

        // A 'processing' status cannot be created if the order is already 'shipped' or 'delivered'.

        if (
            $newStatus === OrderTrackingEnum::PROCESSING &&
            ($latestStatus === OrderTrackingEnum::SHIPPED || $latestStatus === OrderTrackingEnum::DELIVERED || $latestStatus === OrderTrackingEnum::PROCESSING)
        ) {
            return false;
        }

        // Rule 3: Use the allowed transitions defined in the enum for general flow.
        return in_array($newStatus, $latestStatus->getAllowedTransitions());
    }
}
