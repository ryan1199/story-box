<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchNovelRequest extends FormRequest
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
            'title' => 'max:500',
            'categories.*' => 'exists:categories,name',
            'tags.*' => 'exists:tags,name'
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Please title max length is 500 digit',
            'categories.*.exists' => 'Selected categories not valid',
            'tags.*.exists' => 'Selected tags not valid'
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'categories' => 'Categories',
            'categories.*' => 'Selected category name',
            'tags' => 'Tags',
            'tags.*' => 'Selected tag name'
        ];
    }
}
