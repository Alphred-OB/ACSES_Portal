<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:100'],
            'phone_number' => ['nullable', 'digits_between:9,11'],
            'index_number' => [
                'required', 
                'digits_between:9,11',
                'unique:users,index_number',
                // Check pending registrations as well
                Rule::unique('pending_registrations', 'index_number')->where(function ($query) {
                    return $query->where('status', 'pending');
                })
            ],
            'class' => ['required', Rule::in(['Cyber Security', 'Computer Science', 'Information System'])],
            'year' => ['required', Rule::in(['1', '2', '3', '4'])],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
            'accept_terms' => ['accepted'],
            // Honeypot Field
            'website_origin' => ['nullable', 'prohibited'], 
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'accept_terms' => $this->boolean('accept_terms'),
        ]);

        // If a bot fills this, validation will fail due to 'prohibited'
    }

    public function messages(): array
    {
        return [
            'website_origin.prohibited' => 'Go away, bot!',
            'index_number.unique' => 'This reference number is already registered or pending approval.',
        ];
    }
}
