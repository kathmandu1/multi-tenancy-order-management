<?php

namespace Modules\OrderManagement\Pipelines\OrderTracking;

use Closure;

class OrderFilterPipe
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('order_id') && !request()->has('order_id')) {
            return $next($request);
        }
        return $next($request)->where('order_id', request()->order_id);
    }
}
