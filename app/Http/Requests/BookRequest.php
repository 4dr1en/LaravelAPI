<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'title' => 'bail|required|between:1,50|string',
            'description' => 'bail|required|between:10,50|string',
            'author_id' => 'bail|required|integer',
            'publication_year' => 'bail|required|integer|between:-4000,2021',
            'pages_nb' => 'bail|required|integer|between:1,9999999',
            'genres' => 'bail|required'
        ];
    }
}
