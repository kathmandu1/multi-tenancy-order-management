<?php

namespace Modules\OrderManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerShippingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [];
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
        if ($this->route('customer')) {
            $this->merge([
                'customer_id' => $this->route('customer')
            ]);
        }
    }
}
