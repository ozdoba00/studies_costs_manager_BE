<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        $userId = $this->user() ? ',' . $this->user()->id : '';
      
        return [
            'email' => 'required|email|unique:users,email' . $userId,
            'password' => 'required|min:6',
            'name' => 'required',
            'last_name' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email is requred',
            'email.unique' => 'The email is already taken',
            'password.required' => 'The password is required',
            'password.min' => 'The password does not have :min characters',
            'name.required' => 'The name is required',
            'last_name.required' => 'The last name is required'
        ];
    }
}