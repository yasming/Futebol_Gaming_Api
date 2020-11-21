<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormatResponseFormRequest;

class StorePlayer extends FormRequest
{
    use FormatResponseFormRequest;
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'         => 'required|string',
            'document'     => 'bail|required|unique:players|integer|digits:11',
            'shirt_number' => 'required|integer',
        ];
    }
}
