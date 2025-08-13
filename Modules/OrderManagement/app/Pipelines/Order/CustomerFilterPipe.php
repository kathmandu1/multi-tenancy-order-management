<?php

namespace Modules\OrderManagement\Pipelines\Order;

use Closure;

class CustomerFilterPipe
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('customer_id')) {
            return $next($request);
        }
        return $next($request)->where('customer_id', request()->customer_id);
    }
}
