<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class MembershipBenefitCreateRequest extends FormRequest
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
            'benefits' => 'required|array',
            'benefits.*' => 'required|string|max:255',
        ];
    }

    /**
     * Define custom validation messages.
     */
    public function messages(): array
    {
        return [
            'benefits.required' => 'The benefits field is required.',
            'benefits.array' => 'The benefits must be an array.',
            'benefits.*.required' => 'Each benefit must have a value.',
            'benefits.*.string' => 'Each benefit must be a string.',
            'benefits.*.max' => 'Each benefit must not exceed 255 characters.',
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
