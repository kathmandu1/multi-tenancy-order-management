<?php

namespace Modules\OrderManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "product_name" => ['required', 'string', 'max:255'],
            "meta_title" => ['sometimes', 'string', 'max:255'],
            "meta_description" => ['sometimes', 'string', 'max:255'],
            "meta_keywords" => ['sometimes', 'string', 'max:255'],
            "base_price" => ['required', 'numeric'],
            "b2b_price" => ['required', 'numeric'],
            "b2c_price" => ['required', 'numeric'],
            "batch_no" => ['sometimes', 'string', 'max:255'],
            "lot_no" => ['sometimes', 'string', 'max:255'],
            "available_stock" => ['required', 'integer']
        ];
    }



    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
