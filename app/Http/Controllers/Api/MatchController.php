<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatchResource;
use App\Http\Requests\StoreMatchRequest;
use App\Models\Match;
class MatchController extends Controller
{
    public function store(StoreMatchRequest $request)
    {
        $match = Match::create($request->validated());
        $this->syncTeamsAndGols($match);
        $this->syncCards($match,);

        return response()->json(new MatchResource($match->load(['teams','cards'])),201);
    }

    public function update(StoreMatchRequest $request, Match $match)
    {
        $match->update($request->validated());
        $this->syncTeamsAndGols($match);
        $this->syncCards($match);
        return response()->json(new MatchResource($match->load(['teams','cards'])),200);
    }

    private function syncTeamsAndGols($match): void
    {
        $teamsArray = [];
        foreach(request()->match_results as $match_result)
        {
           $teamsArray  +=  [
                                $match_result['team_id'] => 
                                [
                                    'gols' => $match_result['gols']
                                ]
                            ];
        }
        $match->teams()->sync($teamsArray);
    }

    private function syncCards($match): void
    {
        $cardsArray = [];
        foreach(request()->match_cards as $card)
        {
            $cardsArray +=  [
                                $card['card_id'] => 
                                [
                                    'player_id' => $card['player_id']
                                ]
                            ];
        }
        $match->cards()->sync($cardsArray);
    }

}
