<?php

namespace Modules\CentralApplication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "tenant_name" => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            // 'phone' => ['required', 'string', 'max:20'],
            'domain' => ['required', 'string', 'max:255']
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
