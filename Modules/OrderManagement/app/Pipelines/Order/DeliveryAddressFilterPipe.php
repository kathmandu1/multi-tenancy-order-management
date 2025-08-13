<?php

namespace Modules\OrderManagement\Pipelines\Order;

use Closure;

class DeliveryAddressFilterPipe
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('shipping_address')) {
            return $next($request);
        }
        return $next($request)->whereHas('shippingAddress', fn($query) =>
            $query->where('address', 'LIKE', '%' . request()->shipping_address . '%'));
    }
}
