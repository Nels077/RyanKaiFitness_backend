<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'A name is required',
            'last_name.required' => 'A last name is required',
            'last_name.string' => 'Last name must be a string.',
            'first_name.max' => 'A name must be max. :max characters',
            'email.required' => 'The company email is required.',
            'email.email' => 'The email must be a valid email address.',
            'password.required' => 'A password is required.',
            'password.min' => 'The password must be at least :min characters long.',
        ];
    }

    /**
     * Handle failed validation.
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'errors' => $validator->messages(),
        ], 422));
    }
}
