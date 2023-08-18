<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChapterRequest extends FormRequest
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
            'title' => 'required|string|min:2|max:500',
            'content' => 'required|string|min:2|max:10000'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please input title for the chapter',
            'title.string' => 'Please title only contain letter and whitespace',
            'title.min' => 'Please title min length is 2 digit',
            'title.max' => 'Please title max length is 500 digit',
            'content.required' => 'Please input content for the chapter',
            'content.string' => 'Please content only contain letter and whitespace',
            'content.min' => 'Please content min length is 2 digit',
            'content.max' => 'Please content max length is 10000 digit',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'Title',
            'content' => 'Content',
        ];
    }
}
