<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
        // Check if the request is for updating or creating
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $contactId = $this->route('contact')->id ?? null;
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => $isUpdate 
                ? ['required', 'email', 'unique:contacts,email,'.$contactId]
                : ['required', 'email', 'unique:contacts,email'],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'profile_image' => $isUpdate 
                ? [ 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048']
                : [  'required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'additional_file' => ['nullable', 'file', 'mimes:pdf,docx,txt', 'max:5120'],
            'custom_fields' => ['nullable', 'array'],
            'custom_fields.*.title' => ['required', 'string', 'max:255'],
            'custom_fields.*.value' => ['required', 'string', 'max:255'],

            'merged_emails' => ['nullable', 'array'],
            // 'merged_emails.*' => ['email', 'distinct', 'max:255'],

            'merged_phones' => ['nullable', 'array'],
            // 'merged_phones.*' => ['string', 'distinct', 'max:20'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already in use.',
            'phone.required' => 'Phone number is required.',
            'phone.max' => 'Phone number must not exceed 15 characters.',
            'gender.required' => 'Please select a gender.',
            'gender.in' => 'Invalid gender selection.',
            'profile_image.required' => 'A profile image is required.',
            'profile_image.image' => 'The profile image must be a valid image file.',
            'profile_image.mimes' => 'Only JPG, JPEG, and PNG formats are allowed.',
            'profile_image.max' => 'Profile image must not be larger than 2MB.',
            'additional_file.max' => 'Additional file must not exceed 5MB.',

            'custom_fields.*.title.required' => 'Each custom field must have a title.',
            'custom_fields.*.title.string' => 'Custom field title must be a valid string.',
            'custom_fields.*.title.max' => 'Custom field title must not exceed 255 characters.',
            'custom_fields.*.value.required' => 'Each custom field must have a value.',
            'custom_fields.*.value.numeric' => 'Custom field value must be a number.',
            'custom_fields.*.value.min' => 'Custom field value must be at least 0.',
        ];
    }
}
