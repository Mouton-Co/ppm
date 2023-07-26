<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'             => 'required',
            'role'             => 'required',
            'email'            => 'required|unique:users,email',
            'password'         => "regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{8,16}$/",
            'confirm_password' => 'same:password',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function messages(): array
    {
        return [
            'regex'    => "Password must contain one digit, one lowercase, one uppercase,
                one special character, no spaces, and be between 8-16 characters.",
            'same'     => "Passwords don't match",
        ];
    }
}
