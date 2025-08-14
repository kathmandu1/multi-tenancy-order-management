{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> --}}


@component('mail::message')
# Order Created

Thank you for your order, {{ $order->customer->name }}!

**Order ID:** {{ $order->id }}
**Total:** {{ number_format($order->actual_amount, 2) }}

{{-- @component('mail::button', ['url' => route('orders.show', $order->id)])
View Order
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
