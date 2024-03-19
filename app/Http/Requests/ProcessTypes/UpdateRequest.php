<?php

namespace App\Http\Requests\ProcessTypes;

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
            'process_type' => 'required|unique:process_types,process_type,'.$this->get('process_type_id'),
            'required_files' => 'required|array',
        ];
    }
}
