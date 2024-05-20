<?php

namespace App\Http\Requests\RecipientGroup;

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
            'field' => 'required|string',
            'value' => 'required|string',
            'recipients' => 'nullable|string',
        ];
    }
}
