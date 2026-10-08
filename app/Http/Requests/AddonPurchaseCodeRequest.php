<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddonPurchaseCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'addon_name' => 'nullable|string|max:150',
            'software_type' => 'nullable|string|max:50',
            'status' => 'nullable',
        ];
    }
}
