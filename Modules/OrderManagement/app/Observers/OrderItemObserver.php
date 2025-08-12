<?php

namespace Modules\OrderManagement\Observers;


use Modules\OrderManagement\Enums\PriceTypeEnum;
use Modules\OrderManagement\Models\OrderProduct;

class OrderItemObserver
{

    /**
     * Handle the OrderItemObserver "creating" event.
     */
    public function creating(OrderProduct $orderProduct): void
    {
        $order = $orderProduct->order;
        $customer = $order->customer;
        if ($customer->price_type == PriceTypeEnum::B2BPRICE->value) {
            $price =  $orderProduct->product->productVariant->b2b_price;
            // If the customer is B2B, set the price to the product's B2B price
            $orderProduct->price = $price;
            $orderProduct->subtotal = $price * $orderProduct->quantity;
        } elseif ($customer->price_type == PriceTypeEnum::B2CPRICE->value) {
            // If the customer is not B2B, set the price to the product's price
            $price =  $orderProduct->product->productVariant->b2c_price;
        }
        $orderProduct->price = $price;
        $orderProduct->subtotal = $price * $orderProduct->quantity;
        $orderProduct->status = true; // Default status to true at first time order create
    }

    /**
     * Handle the OrderItemObserver "created" event.
     */
    public function created(OrderProduct $orderProduct): void {}

    /**
     * Handle the OrderItemObserver "updated" event.
     */
    public function updated(OrderProduct $orderProduct): void {}

    /**
     * Handle the OrderItemObserver "deleted" event.
     */
    public function deleted(OrderProduct $orderProduct): void {}

    /**
     * Handle the OrderItemObserver "restored" event.
     */
    public function restored(OrderProduct $orderProduct): void {}

    /**
     * Handle the OrderItemObserver "force deleted" event.
     */
    public function forceDeleted(OrderProduct $orderProduct): void {}
}
