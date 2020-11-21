<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormatResponseFormRequest;

class StorePlayerRequest extends FormRequest
{
    use FormatResponseFormRequest;
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $documentRules = 'bail|required|integer|digits:11|unique:players';
        if($this->getMethod() == 'PUT') $documentRules .= ',document,'.request()->route('player')->id; 
        return [
            'name'         => 'required|string',
            'document'     => $documentRules,
            'shirt_number' => 'required|integer',
        ];
    }
}
