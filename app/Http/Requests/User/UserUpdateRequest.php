<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required',
            'role' => 'required',
            'email' => 'required|unique:users,email,'.$this->get('user_id'),
        ];

        if (! empty($this->get('password')) || $this->get('confirm_password')) {
            $rules['password'] = "regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{8,16}$/";
            $rules['confirm_password'] = 'same:password';
        }

        return $rules;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function messages(): array
    {
        return [
            'regex' => 'Password must contain one digit, one lowercase, one uppercase,
                one special character, no spaces, and be between 8-16 characters.',
            'same' => "Passwords don't match",
        ];
    }
}
