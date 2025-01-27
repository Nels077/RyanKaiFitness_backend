<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class MembershipBenefitUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Define the validation rules.
     */
    public function rules(): array
    {
        return [
            'text' => 'nullable|string|max:255',
        ];
    }

    /**
     * Define custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.string' => 'The benefit must be a valid string.',
            'title.max' => 'The benefit must not exceed 255 characters.',
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
