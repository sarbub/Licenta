<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateUserDataRequest extends BaseRequest
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
            $this->rulesForEmail(),
            $this->rulesForBio()
        );
    }

    public function messages()
    {
        return [
            
            // Scenario 1: Email validation
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            
            // Scenario 2: Bio validation (textbox or free text)
            'bio.required' => 'The field is required.',
            'bio.string' => 'Bio must be a valid string.',
            'bio.min'=>'Bio must be at least 8 chars long',
            'bio.max' => 'Bio may not be greater than 500 characters.',
        ];
    }
}

// $validator = Validator::make($request->all(), (new ValidateUserDataRequest)->rulesForEmail());

