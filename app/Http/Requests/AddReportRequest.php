<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddReportRequest extends FormRequest
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
            'reason' => 'required|string|min:2|max:100'
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Please input your last name',
            'reason.string' => 'Please last name only contain letter and whitespace',
            'reason.min' => 'Please last name min length is 2 digit',
            'reason.max' => 'Please last name max length is 100 digit',
        ];
    }

    public function attributes(): array
    {
        return [
            'reason' => 'Reason',
        ];
    }
}
