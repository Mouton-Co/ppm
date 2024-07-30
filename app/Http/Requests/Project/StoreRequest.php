<?php

namespace App\Http\Requests\Project;

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
            'machine_nr' => 'nullable|string',
            'country' => 'nullable|string',
            'coc' => 'nullable|string',
            'noticed_issue' => 'nullable|string',
            'proposed_solution' => 'nullable|string',
            'currently_responsible' => 'nullable|string',
            'status' => 'nullable|string',
            'resolved_at' => 'nullable|string',
            'related_po' => 'nullable|string',
            'customer_comment' => 'nullable|string',
            'commisioner_comment' => 'nullable|string',
            'logistics_comment' => 'nullable|string',
            'submission_id' => 'nullable|exists:submissions,id',
            'costing' => 'required|string|in:APL,CoC',
        ];
    }
}
