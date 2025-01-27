<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class FitnessClassCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'working_days' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Define custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a valid string.',
            'title.max' => 'The title must not exceed 255 characters.',

            'subtitle.string' => 'The subtitle must be a valid string.',
            'subtitle.max' => 'The subtitle must not exceed 255 characters.',

            'duration.string' => 'The subtitle must be a valid string.',
            'duration.max' => 'The subtitle must not exceed 255 characters.',

            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be at least 0.',
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
