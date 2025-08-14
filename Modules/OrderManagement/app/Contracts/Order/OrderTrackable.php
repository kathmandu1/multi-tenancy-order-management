<?php

namespace Modules\OrderManagement\Contracts\Order;

use Modules\OrderManagement\Contracts\BaseContract;
use Modules\OrderManagement\Enums\OrderTrackingEnum;

interface OrderTrackable extends BaseContract
{
    public function getLatestStatusOfOrder($orderId): ?OrderTrackingEnum;
}
