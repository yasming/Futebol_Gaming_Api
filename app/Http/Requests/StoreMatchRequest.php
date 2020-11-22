<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormatResponseFormRequest;

class StoreMatchRequest extends FormRequest
{
    use FormatResponseFormRequest;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date'                    => 'required|date_format:"d/m/Y"', 
            'start_time'              => 'required|date_format:H:i',
            'end_time'                => 'bail|required|date_format:H:i|after:start_time',
            'match_results'           => 'bail|required|array|max:2',
            'match_results.*.team_id' => 'integer|distinct', 
            'match_results.*.gols'    => 'integer',
            'match_cards'             => 'bail|nullable|array', 
            'match_cards.*.player_id' => 'integer', 
            'match_cards.*.card_id'   => 'integer', 
        ];
    }
}
