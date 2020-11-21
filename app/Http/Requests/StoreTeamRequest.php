<?php

namespace App\Http\Requests;

use App\Rules\CheckIfPlayersBelongsToOtherTeamRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormatResponseFormRequest;

class StoreTeamRequest extends FormRequest
{
    use FormatResponseFormRequest;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $teamId = null;
        if($this->getMethod() == 'PUT') $teamId = request()->route('team')->id; 
        return [
            'name'        => 'required', 
            'players_ids' => ['array','max:5' , new CheckIfPlayersBelongsToOtherTeamRule($teamId)]
        ];
    }
}
