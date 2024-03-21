<?php

namespace App\Http\Requests\ProjectStatus;

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
            'name' => 'required|unique:project_statuses,name,' . $this->get('project_status_id') . ',id',
        ];
    }
}
