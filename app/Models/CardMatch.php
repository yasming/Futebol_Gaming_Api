<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardMatch extends Model
{
    use HasFactory;
    protected $table = 'card_match';

    public function scopeGetRankingPlayers($query, $playerName)
    {
        return  $query->groupBy('player_id')
                      ->when($playerName,function($query) use ($playerName){
                          return $query->where('players.name','like','%'.$playerName.'%');
                      })
                      ->join('cards','cards.id','card_match.card_id')
                      ->join('players','players.id','card_match.player_id')
                      ->selectRaw('players.name as name, SUM(cards.points) as points')
                      ->orderBy('points', 'asc')
                      ->get();
    }
}
