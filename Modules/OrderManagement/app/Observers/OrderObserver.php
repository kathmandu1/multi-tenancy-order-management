<?php

namespace Modules\OrderManagement\Observers;


use Modules\OrderManagement\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{

    /**
     * Before creating the order
     */
    public function creating(Order $order): void
    {
        // Generate order_code if not provided
        if (empty($order->order_code)) {
            $order->order_code = 'ORD-' . strtoupper(Str::random(8));
        }

        // Auto-calculate actual_amount
        $order->actual_amount = $order->total_order_amount - ($order->total_discount_amount ?? 0);
        $order->status = $order->status ?? true; // Default status to true if not set
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void {}

    /**
     * Handle the OrderObserver "updated" event.
     */
    public function updated(Order $order): void {}

    /**
     * Handle the OrderObserver "deleted" event.
     */
    public function deleted(Order $order): void {}

    /**
     * Handle the OrderObserver "restored" event.
     */
    public function restored(Order $order): void {}

    /**
     * Handle the OrderObserver "force deleted" event.
     */
    public function forceDeleted(Order $order): void {}
}
