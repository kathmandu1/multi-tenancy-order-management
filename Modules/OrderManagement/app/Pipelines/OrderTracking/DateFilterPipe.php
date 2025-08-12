<?php

namespace Modules\OrderManagement\Pipelines\OrderTracking;

use Carbon\Carbon;
use Closure;

class DateFilterPipe
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('from_date') && !request()->has('to_date')) {
            return $next($request);
        }
        $fromDate = Carbon::parse(request()->from_date)->startOfDay();
        $toDate = Carbon::parse(request()->to_date)->endOfDay();
        return $next($request)->whereBetween('date', [$fromDate, $toDate]);
    }
}
