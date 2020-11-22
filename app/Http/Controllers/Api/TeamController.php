<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Teams\TeamResource;
use App\Http\Resources\Teams\TeamResourceCollection;
use App\Http\Requests\StoreTeamRequest;
use App\Models\Team;
use App\Models\Player;
class TeamController extends Controller
{
    public function index()
    {
        return response()->json(
            [
                'teams' => new TeamResourceCollection(Team::getAllTeamsWithPlayers(request()->query('name')))
            ],200);
    }

    public function store(StoreTeamRequest $request)
    {
        $team = Team::create($request->validated());
        Player::associateTeam($team->id,$request->players_ids);
        return response()->json(new TeamResource($team->load('players')),201);
    }

    public function update(StoreTeamRequest $request, Team $team)
    {
        $team->update($request->validated());
        $playersFromTeam      = $team->players();
        $newPlayersToBeInsert = array_diff(request()->players_ids, $playersFromTeam->pluck('id')->toArray());

        if($playersFromTeam->count() + sizeof($newPlayersToBeInsert) > Team::MAX_NUMBER_OF_PLAYERS) 
        {
            return response()->json(['max_number_of_players' => Team::MESSAGE_MAX_NUMBER_OF_PLAYERS],422);
        }
        
        Player::associateTeam($team->id,$newPlayersToBeInsert);
        return response()->json(new TeamResource($team->load('players')),200);
    }

}
