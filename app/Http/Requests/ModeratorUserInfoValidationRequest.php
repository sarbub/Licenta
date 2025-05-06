<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModeratorUserInfoValidationRequest extends BaseRequest
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
        return array_merge(
            $this->rulesForNames(),
            $this->rulesForPassword()
        );
    }

    public function messages(){
        return [
            'first_name.required' => 'Please enter your first name.',
            'first_name.string'   => 'First name must be a valid text.',
            'first_name.max'      => 'First name cannot exceed 255 characters.',
            'first_name.regex' => 'First name may only contain letters and spaces.',

            'last_name.required' => 'Please enter your last name.',
            'last_name.string'   => 'Last name must be a valid text.',
            'last_name.max'      => 'Last name cannot exceed 255 characters.',
            'last_name.regex'  => 'Last name may only contain letters and spaces.',

            'password.required' => 'A password is required.',
            'password.min' => 'Your password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'Password must include at least one uppercase letter, one number, and one special character.',


        ];
    }
}
