<?php

namespace App\Http\Requests;

class RegistrationRequest extends AbstractRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'firstName' => 'required|string|min:2||max:20',
            'lastName' => 'string|min:2|max:20',
            'middleName' => 'string|min:2|max:20',
        ];
    }
}
