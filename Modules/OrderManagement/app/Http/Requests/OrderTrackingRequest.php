<?php

namespace Modules\OrderManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\OrderManagement\Enums\OrderTrackingEnum;

class OrderTrackingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'date' => 'required|date',
            'order_status' => ['required', 'string', 'in:' . implode(',', OrderTrackingEnum::toArray())]
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
