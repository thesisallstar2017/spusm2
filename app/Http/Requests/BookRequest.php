<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BookRequest extends Request
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
            'card_number' => 'required',
            'call_number' => 'required',
            'title'       => 'required',
            'author'      => 'required',
            'subject'     => 'required',
            'material_id' => 'required',
            'publisher'   => 'required',
            'publish_place' => 'required',
            'published_year' => 'required',
            'quantity'       => 'required'
        ];
    }
}
