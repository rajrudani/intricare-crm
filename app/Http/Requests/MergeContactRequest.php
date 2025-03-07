<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MergeContactRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'contact_id' => ['required','exists:contacts,id'],
            'master_contact_id' => ['required','exists:contacts,id'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages()
    {
        return [
            'master_contact_id.required' => 'Please provide a valid master contact.',
            'master_contact_id.exists'   => 'Please select a valid master contact.',
        ];
    }
}
