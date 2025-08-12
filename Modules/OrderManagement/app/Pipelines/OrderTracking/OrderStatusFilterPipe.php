<?php

namespace Modules\OrderManagement\Pipelines\OrderTracking;

use Closure;

class OrderStatusFilterPipe
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('order_status')) {
            return $next($request);
        }
        return $next($request)->where('order_status', request()->order_status);
    }
}
