<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchTeam extends Model
{
    use HasFactory;
    protected $table = 'match_team';

    public function scopeGetRankingTeams($query)
    {
        return  $query->groupBy('team_id')
                      ->join('teams','teams.id','match_team.team_id')
                      ->selectRaw('teams.name as name, SUM(gols) as gols')
                      ->orderBy('gols', 'desc')
                      ->get();
    }
}
