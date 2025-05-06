<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailForSendingRequestRequest extends BaseRequest
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
            'email' => 'required|email|unique:pending_requests,email',  // Add both rules here
        ];
    }

    public function messages(){
        return [
            'email.required' => 'Please enter your email address.',
            'email.string'   => 'Email must be a valid text.',
            'email.email'    => 'Email must be a valid email address.',
            'email.max'      => 'Email cannot exceed 255 characters.',
            'email.unique'   => 'This email address is already in use.',
        ];
    }
}
