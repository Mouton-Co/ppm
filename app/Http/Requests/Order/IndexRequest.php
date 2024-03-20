<?php

namespace App\Http\Requests\Order;

use App\Models\Supplier;
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
            'status' => 'nullable|string|in:'.implode(',', array_merge(
                array_keys(config('models.orders.status')),
                ['0']
            )),
            'supplier' => 'nullable|string|in:'.implode(',', array_merge(
                Supplier::all()->pluck('id')->toArray(),
                ['0']
            )),
            'search' => 'nullable|string',
        ];
    }
}
