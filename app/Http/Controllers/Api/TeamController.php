<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamResourceCollection;
use App\Http\Requests\StoreTeamRequest;
use App\Models\Team;
use App\Models\Player;
class TeamController extends Controller
{
    public function index()
    {
        return response()->json(
            [
                'teams' => new TeamResourceCollection(Team::all())
            ],200);
    }

    public function store(StoreTeamRequest $request)
    {
        $team = Team::create($request->validated());
        Player::associateTeam($team->id);
        return response()->json(new TeamResource($team->load('players')),201);
    }

    public function update(StoreTeamRequest $request, Team $team)
    {
        $team->update($request->validated());
        if($team->players()->count() + sizeof(request()->players_ids) > Team::MAX_NUMBER_OF_PLAYERS) 
        {
            return response()->json(['max_number_of_players' => Team::MESSAGE_MAX_NUMBER_OF_PLAYERS],422);
        }
        
        Player::associateTeam($team->id);
        return response()->json(new TeamResource($team->load('players')),200);
    }

}
