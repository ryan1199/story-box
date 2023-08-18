<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentNovelStoreRequest extends FormRequest
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
        // dd($this->request);
        return [
            'content' => 'required|string|min:2|max:10000'
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Please input your last name',
            'content.string' => 'Please last name only contain letter and whitespace',
            'content.min' => 'Please last name min length is 2 digit',
            'content.max' => 'Please last name max length is 10000 digit',
        ];
    }

    public function attributes(): array
    {
        return [
            'content' => 'Content',
        ];
    }
}
