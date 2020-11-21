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
                'teams' => new TeamResourceCollection(Team::all()->load('players'))
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
        return response()->json(new TeamResource($team),200);
    }

}
