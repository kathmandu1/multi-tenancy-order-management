<?php

namespace Modules\OrderManagement\DataBuilder;

use Illuminate\Http\Request;
use Modules\OrderManagement\DTO\CustomerDTO;
use Modules\OrderManagement\DTO\ProductDTO;

final class ProductDataBuilder
{
    public static function getDtoData(Request $request): ProductDTO
    {
        return new ProductDTO(
            product_name: $request->product_name,
            meta_title: $request->meta_title,
            meta_description: $request->meta_description,
            meta_keywords: $request->meta_keywords,
            remarks: $request->remarks,
            status: $request->status,
            base_price: $request->base_price,
            b2b_price: $request->b2b_price,
            b2c_price: $request->b2c_price,
            batch_no: $request->batch_no,
            lot_no: $request->lot_no,
            available_stock: $request->available_stock
        );
    }
}
