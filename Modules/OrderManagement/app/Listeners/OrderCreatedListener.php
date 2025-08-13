<?php

namespace Modules\OrderManagement\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\OrderManagement\Events\OrderCreatedEvent;

class OrderCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(OrderCreatedEvent $orderCreatedEvent): void
    {
        // $orderItems = $orderCreatedEvent->order->orderItems;
        // $grandTotal = $orderItems->sum('subtotal');
        // $orderCreatedEvent->order->update([
        //     'total_order_amount' => $grandTotal,
        //     'actual_amount' => $grandTotal,
        // ]);

      // send notifcation activity to job and queue
    }
}
