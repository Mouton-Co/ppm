<?php

namespace App\Http\Requests\Parts;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
        return [
            'page' => 'nullable|integer|min:1',
            'order_by' => 'nullable|string|in:' . implode(',', array_merge(
                array_keys(config('models.parts.columns')),
                ['created_at']
            )),
            'order_direction' => 'nullable|string|in:asc,desc',
            'search' => 'nullable|string',
        ];
    }
}
