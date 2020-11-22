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
        $this->attachTeamsAndGols($match);
        $this->attachCards($match);

        return response()->json(new MatchResource($match->load(['teams','cards'])),201);
    }

    public function update(StoreMatchRequest $request, Team $team)
    {
        $team->update($request->validated());
        return response()->json(new MatchResource($team->load('players')),200);
    }

    private function attachTeamsAndGols($match): void
    {
        foreach(request()->match_results as $match_result)
        {
            $match->teams()->attach([$match_result['team_id'] => 
                                [
                                    'gols' => $match_result['gols']
                                ]
                          ]);
        }
    }

    private function attachCards($match): void
    {
        foreach(request()->match_cards as $card)
        {
            $match->cards()->attach([$card['card_id'] => 
                                [
                                    'player_id' => $card['player_id']
                                ]
                          ]);
        }
    }

}
