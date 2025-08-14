@component('mail::message')
    # Order Upadate

    Your order status has been change, {{ $ordertracking->order->customer->name }}!

    **Order ID:** {{ $ordertracking->order->id }}
    **Order Status:** {{ $ordertracking->order_status }}


    Thanks,
    {{ config('app.name') }}
@endcomponent
