<?php

namespace Modules\OrderManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderItemsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {

        if ($this->route('order')) {
            $this->merge([
                'order_id' => $this->route('order')
            ]);
        }
    }
}
