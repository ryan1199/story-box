<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
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
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'max:150'],
            'confirm_password' => ['required', 'same:password'],
        ];
    }
    public function messages(): array
    {
        return [
            'password.required' => 'Please input your password',
            'password.password' => 'Please password contain at least one number, one letter, one lowercase, one uppercase and one symbol',
            'password.min' => 'Please password min length is 8 digit',
            'password.max' => 'Please password max length is  150 digit',
            'confirm_password.required' => 'Please input your confirm password',
            'confirm_password.same' => 'Your confirm password is do not match',
        ];
    }

    public function attributes(): array
    {
        return [
            'password' => 'Password',
            'confirm_password' => 'Confirm password'
        ];
    }
}
