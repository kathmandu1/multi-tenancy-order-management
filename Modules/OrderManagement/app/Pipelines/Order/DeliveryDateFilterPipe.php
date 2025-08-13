<?php

namespace Modules\OrderManagement\Pipelines\Order;;

use Carbon\Carbon;
use Closure;

class DeliveryDateFilterPipe
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('delivery_date_from') && !request()->has('delivery_date_to')) {
            return $next($request);
        }
        $fromDate = Carbon::parse(request()->delivery_date_from)->startOfDay();
        $toDate = Carbon::parse(request()->delivery_date_to)->endOfDay();
        return $next($request)->whereBetween('delivery_date', [$fromDate, $toDate]);
    }
}
