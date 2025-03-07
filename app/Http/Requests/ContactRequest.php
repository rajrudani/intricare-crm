<?php

namespace App\Http\Requests;

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
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:contacts,email'],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'profile_image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'additional_file' => ['nullable', 'file', 'mimes:pdf,docx,txt', 'max:5120'],
            'custom_fields' => ['nullable', 'array'],
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
        ];
    }
}
