<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSellerRequest extends FormRequest
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
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'mobile_no' => 'required|string|unique:users,mobile_no',
            'country'   => 'required|string',
            'state'     => 'required|string',
            'skills'    => 'required|array|min:1', // Must be an array with at least one skill
            'password'  => 'required|string|min:8',
        ];
    }
    public function messages(): array
    {
        return [
            'email.unique'     => 'This email address is already registered.',
            'mobile_no.unique' => 'This mobile number is already in use.',
            'mobile_no.digits' => 'The mobile number must be exactly 10 digits.',
        ];
    }
}
