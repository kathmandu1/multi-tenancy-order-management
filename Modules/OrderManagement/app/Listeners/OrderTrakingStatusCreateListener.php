<?php

namespace Modules\OrderManagement\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\OrderManagement\Events\OrderTrakingStatusCreate;
use Modules\OrderManagement\Jobs\OrderTrakingStatusCreateJob;

class OrderTrakingStatusCreateListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(OrderTrakingStatusCreate $orderTrakingStatusCreate): void
    {
        OrderTrakingStatusCreateJob::dispatch($orderTrakingStatusCreate->orderTracking);
    }
}
