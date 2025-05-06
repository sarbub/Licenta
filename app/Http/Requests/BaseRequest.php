<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function rulesForNames(): array
    {
        return[
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
        ];
    }

    // second scenario, validation for email

    public function rulesForEmail(): array
    {
        return[
            'email' => 'required|email',
        ];
    }

    // third scenario, validation for password
    public function rulesForPassword(): array
    {
        return[
            'password' => 'required|string|min:8|confirmed',
        ];
        
    }

    public function rulesForSinglePassword(): array
    {
        return[
            'password' => 'required|string|min:8',
        ];
        
    }

    // forth scenario, validation for bio 
    public function rulesForBio(): array 
    {
        return [
            'bio' => 'required|string|min:8|max:500',
        ];
    }
}
