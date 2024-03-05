<?php

namespace App\Http\Requests\AutofillSuppliers;

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
            'text'       => 'required|unique:autofill_suppliers,text',
            'supplier_id' => 'required|exists:suppliers,id',
        ];
    }
}
