<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class NotReadyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'due_date' => 'required|date|after:tomorrow',
            'reason' => 'required|string',
        ];
    }
}
