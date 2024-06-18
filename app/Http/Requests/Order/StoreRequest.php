<?php

namespace App\Http\Requests\Order;

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
            'po_number' => 'required|string|unique:orders,po_number|exists:parts,po_number',
            'notes' => 'string',
            'status' => 'required|in_array:'.implode(',', \App\Models\Order::STATUSES),
        ];
    }
}
