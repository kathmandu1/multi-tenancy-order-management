<?php

namespace Modules\OrderManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\OrderManagement\Enums\PriceTypeEnum;

class CustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "name" => ['required', 'string', 'max:255'],
            "address" => ['required', 'string', 'max:255'],
            "phone" => ['required', 'string', 'max:20'],
            "email" => ['required', 'string', 'email', 'max:255'],
            "price_type" => ['required', 'string', 'in:' . implode(',', PriceTypeEnum::toArray())]
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
