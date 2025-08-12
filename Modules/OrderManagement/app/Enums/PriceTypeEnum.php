<?php

namespace Modules\OrderManagement\Enums;

enum PriceTypeEnum: string
{

    case B2BPRICE = 'b2b price';
    case B2CPRICE = 'b2c price';
    case NORMALPRICE = 'normal price';



    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
