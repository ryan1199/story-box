<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class UserRegisterRequest extends FormRequest
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
            'first_name' => 'required|regex:/^[a-zA-Z]+(?:[\s.]+[a-zA-Z]+)*$/|min:2|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z]+(?:[\s.]+[a-zA-Z]+)*$/|min:2|max:255',
            'username' => ['required', 'alpha_dash', Rule::unique('users', 'username'), 'min:2', 'max:255'],
            'email' => ['required', 'email:rfc,dns', Rule::unique('users', 'email'), 'min:5', 'max:255'],
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'max:255'],
            'confirm_password' => ['required', 'same:password'],
            'picture' => ['required', 'mimes:jpg,jpeg,png', File::image()->min(1)->max(5120)]
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please input your first name',
            'first_name.regex' => 'Please first name only contain letter and whitespace',
            'first_name.min' => 'Please first name min length is 2 digit',
            'first_name.max' => 'Please first name max length is 255 digit',
            'last_name.required' => 'Please input your last name',
            'last_name.regex' => 'Please last name only contain letter and whitespace',
            'last_name.min' => 'Please last name min length is 2 digit',
            'last_name.max' => 'Please last name max length is 255 digit',
            'username.required' => 'Please input your unique username',
            'username.alpha_dash' => 'Please username only contain letters, numbers, dashes, underscores and not space',
            'username.unique' => 'Your unique username is already taken',
            'username.min' => 'Please username min length is 2 digit',
            'username.max' => 'Please username max length is 255 digit',
            'email.required' => 'Please input your email',
            'email.email' => 'Your email is no valid',
            'email.unique' => 'Your email is already taken',
            'email.min' => 'Please email min length is 5 digit',
            'email.max' => 'Please email max length is 255 digit',
            'password.required' => 'Please input your password',
            'password.password' => 'Please password contain at least one number, one letter, one lowercase, one uppercase and one symbol',
            'password.min' => 'Please password min length is 8 digit',
            'password.max' => 'Please password max length is  255 digit',
            'confirm_password.required' => 'Please input your confirm password',
            'confirm_password.same' => 'Your confirm password is do not match',
            'picture.required' => 'Please input your picture',
            'picture.mimes' => 'Please use only jpg, jpeg and png file type',
            'picture.image' => 'Please input only image file type',
            'picture.between' => 'The maximum file size is 5 MB',
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'username' => 'Username',
            'email' => 'Email address',
            'password' => 'Password',
            'confirm_password' => 'Confirm password',
            'picture' => 'Picture'
        ];
    }
}
