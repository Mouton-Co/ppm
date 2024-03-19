<?php

namespace App\Http\Requests\Representative;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone_1' => 'nullable|string',
            'phone_2' => 'nullable|string',
            'supplier_id' => 'required|exists:suppliers,id',
        ];
    }
}
