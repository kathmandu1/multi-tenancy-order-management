<?php

namespace Modules\OrderManagement\Pipelines\Order;

use Closure;

class StatusFilterPipe
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('status')) {
            return $next($request);
        }
        return $next($request)->where('status', request()->status);
    }
}
