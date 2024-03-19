<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $this->merge(['id' => $this->route('id')]);

        return [
            'id' => 'required|exists:orders,id',
            'po_number' => 'string|unique:orders,po_number|exists:parts,po_number',
            'notes' => 'string',
            'status' => 'in_array:'.implode(',', config('models.orders.status')),
        ];
    }
}
