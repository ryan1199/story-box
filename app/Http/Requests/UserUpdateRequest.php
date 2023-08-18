<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UserUpdateRequest extends FormRequest
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
            'username' => ['required', 'alpha_dash', Rule::unique('users', 'username')->ignore($this->user()->id), 'min:2', 'max:255'],
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
            'picture' => 'Picture'
        ];
    }
}
