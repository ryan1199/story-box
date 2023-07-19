<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailVerificationRequest extends FormRequest
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
            'email' => ['required', 'email:rfc,dns', 'exists:users,email', 'min:5', 'max:150'],
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => 'Please input your email',
            'email.email' => 'Your email is no valid',
            'email.exists' => 'Your email is not found in our system, or try another email',
            'email.min' => 'Please email min length is 5 digit',
            'email.max' => 'Please email max length is 150 digit'
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'Email address'
        ];
    }
}
