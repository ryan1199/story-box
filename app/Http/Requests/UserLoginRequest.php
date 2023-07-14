<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserLoginRequest extends FormRequest
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
            'username' => ['required', 'alpha_dash', 'min:2', 'max:150'],
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'max:150'],
            'remember' => ['nullable', 'boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Please input your unique username',
            'username.alpha_dash' => 'Please username only contain letters, numbers, dashes, underscores and not space',
            'username.min' => 'Please username min length is 2 digit',
            'username.max' => 'Please username max length is 150 digit',
            'password.required' => 'Please input your password',
            'password.password' => 'Please password contain at least one number, one letter, one lowercase, one uppercase and one symbol',
            'password.min' => 'Please password min length is 8 digit',
            'password.max' => 'Please password max length is  150 digit',
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
        ];
    }
}
