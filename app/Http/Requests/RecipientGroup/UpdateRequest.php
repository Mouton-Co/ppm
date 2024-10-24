<?php

namespace App\Http\Requests\RecipientGroup;

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
            'field' => 'required|string',
            'value' => 'nullable|string',
            'recipients' => 'nullable|string',
        ];
    }
}
