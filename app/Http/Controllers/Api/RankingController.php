<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchTeam;
use App\Http\Resources\RankingTeamsResourceCollection;

class RankingController extends Controller
{
    public function getRankingTeams()
    {
        return response()->json(
            [
                'ranking' => new RankingTeamsResourceCollection(MatchTeam::getRankingTeams())
            ],200);
    }

    public function getRankingPlayers()
    {
        return response()->json(
            [
                'ranking' => new RankingTeamsResourceCollection(MatchTeam::getRankingTeams())
            ],200);
    }
}
