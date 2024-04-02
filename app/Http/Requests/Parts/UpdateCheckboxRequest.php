<?php

namespace App\Http\Requests\Parts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCheckboxRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $this->merge(['id' => $this->route('id')]);

        return [
            'id' => 'required|integer|exists:parts,id',
            'field' => 'required|string|in:raw_part_received,treatment_1_part_received,treatment_2_part_received,completed_part_received,qc_passed,qc_issue,part_ordered',
            'value' => 'required|boolean',
        ];
    }
}
