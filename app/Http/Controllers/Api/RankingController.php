<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchTeam;
use App\Models\CardMatch;
use App\Http\Resources\Ranking\RankingResourceCollection;

class RankingController extends Controller
{
    public function getRankingTeams()
    {
        return response()->json(
            [
                'ranking' => new RankingResourceCollection(MatchTeam::getRankingTeams(request()->query('name')))
            ],200);
    }

    public function getRankingPlayers()
    {
        return response()->json(
            [
                'ranking' => new RankingResourceCollection(CardMatch::getRankingPlayers(request()->query('name')))
            ],200);
    }
}
