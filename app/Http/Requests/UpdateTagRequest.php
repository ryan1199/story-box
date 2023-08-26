<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
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
            'name' => ['required', 'alpha_dash', Rule::unique('tags', 'name')->ignore($this->tag->id), Rule::unique('categories', 'name'), 'min:1', 'max:50']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please input unique name',
            'name.alpha_dash' => 'Please name only contain letters, numbers, dashes, underscores and not space',
            'name.unique' => 'Name is already taken',
            'name.min' => 'Please name min length is 1 digit',
            'name.max' => 'Please name max length is 50 digit'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name'
        ];
    }
}
