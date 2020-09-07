<?php

namespace App\Http\Requests;

class RegistrationConfirmRequest extends AbstractRequest
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

    public function all()
    {
        $data = parent::all();

        $data['token'] = $this->route('token');

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|string|min:60|max:60',
        ];
    }
}
