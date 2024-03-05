<?php

namespace App\Http\Requests\AutofillSuppliers;

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
            'text'       => 'required|unique:autofill_suppliers,text,'.$this->get('autofill_supplier_id'),
            'supplier_id' => 'required|exists:suppliers,id',
        ];
    }
}
