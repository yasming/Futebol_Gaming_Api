<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'document',
        'shirt_number',
        'team_id'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'team_id'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function scopeGetAllPlayersWithTeam($query,$playerName)
    {
        return  $query->when($playerName,function($query) use ($playerName){
                        return $query->where('name','like','%'.$playerName.'%');
                      })->with('team')
                        ->get();
    }
    
    public function scopeGetPlayersThatBelongsToOthersTeam($query,$value,$teamId)
    {
        return  $query->whereIn('id', $value)
                      ->when($teamId != null, function($query) use($teamId){
                          return $query->where('team_id', '!=',$teamId);
                      })
                      ->whereNotnull('team_id')
                      ->get();
    }

    public function scopeAssociateTeam($query,$teamId,$playersIds)
    {
        return $query->whereIn('id',$playersIds)
                     ->whereNull('team_id')
                     ->update(
                         [
                            'team_id' => $teamId
                         ]
                     );
    }
}
