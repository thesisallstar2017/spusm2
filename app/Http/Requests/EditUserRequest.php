<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditUserRequest extends Request
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
          'name' => 'required|max:255',
          'user_id' => 'required|numeric',
          'email' => 'required|email|max:255|unique:users,email,' . $this->input('id'),
          'password' => 'min:6'
        ];
    }
}
