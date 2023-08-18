<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBoxRequest extends FormRequest
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
            'title' => 'required|regex:/^[a-zA-Z0-9]+(?:[\s.]+[a-zA-Z0-9]+)*$/|min:2|max:500',
            'description' => 'required|regex:/^[a-zA-Z0-9]+(?:[\s.]+[a-zA-Z0-9]+)*$/|min:2|max:10000',
            'categories' => 'required|max:50', //jumlah tag 50
            'categories.*' => 'exists:categories,name|max:50', //panjang tag
            'tags' => 'required|max:50',
            'tags.*' => 'exists:tags,name|max:50',
            'visible' => ['required', Rule::in(['Public', 'Private'])],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please input title for the box',
            'title.regex' => 'Please title only contain letter and whitespace',
            'title.min' => 'Please title min length is 2 digit',
            'title.max' => 'Please title max length is 500 digit',
            'description.required' => 'Please input description for the box',
            'description.regex' => 'Please description only contain letter and whitespace',
            'description.min' => 'Please description min length is 2 digit',
            'description.max' => 'Please description max length is 10000 digit',
            'categories.required' => 'Select at least 1 category',
            'categories.max' => 'You can only select up to 50 categories',
            'categories.*.exists' => 'Selected categories not valid',
            'categories.*.max' => 'The maximum category length is 50 digits',
            'tags.required' => 'Select at least 1 tag',
            'tags.max' => 'You can only select up to 50 tags',
            'tags.*.exists' => 'Selected tags not valid',
            'tags.*.max' => 'The maximum tag length is 50 digits',
            'visible.required' => 'Please choose visibility for this box',
            'visible.in' => 'Please choose between Public or Private'
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
            'categories' => 'Categories',
            'categories.*' => 'Selected category name',
            'tags' => 'Tags',
            'tags.*' => 'Selected tag name',
            'visible' => 'Visible'
        ];
    }
}
