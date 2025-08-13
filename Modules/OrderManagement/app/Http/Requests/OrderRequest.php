<?php

namespace Modules\OrderManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "customer_id" => ['required', 'integer', 'exists:customers,id'],
            "order_code" => ['required', 'string', 'max:255'],
            // "order_date" => ['required', 'date', 'after:today'],
            "delivery_date" => ['required', 'date', 'after:order_date'],
            // "shipping_address_id" => ['required', 'integer', 'exists:customer_shipping_addresses,id'],
            'shipping_address_id' => [
                'required',
                'integer',
                // Use the 'exists' rule with a custom WHERE clause
                // 'customer_shipping_addresses' is the table name
                // 'id' is the column to check for existence
                // 'customer_id' is the column to match in the same table
                // $this->input('customer_id') gets the value from the request payload
                'exists:customer_shipping_addresses,id,customer_id,' . $this->input('customer_id')
            ],
            "total_order_amount" => ['required', 'numeric'],
            "total_discount_amount" => ['required', 'numeric'],
            "actual_amount" => ['required', 'numeric'],
            "status" => ['required', 'boolean'],
            "remark" => ['sometimes', 'string', 'max:255'],
            "order_items" => ['required', 'array'],
            "order_items.*.product_id" => ['required', 'integer', 'exists:products,id'],
            "order_items.*.quantity" => ['required', 'integer', 'min:1']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
             // Custom message for the combined shipping_address_id exists rule
            'shipping_address_id.exists' => 'The selected shipping address is invalid or does not belong to the specified customer.',
        ];
    }
}
