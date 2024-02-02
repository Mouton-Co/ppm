<?php

namespace App\Http\Requests\Representative;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'representative_id' => 'required|exists:representatives,id',
            'name'              => 'required|string',
            'email'             => 'required|email',
            'phone_1'           => 'nullable|string',
            'phone_2'           => 'nullable|string',
            'supplier_id'       => 'required|exists:suppliers,id',
        ];
    }
}
