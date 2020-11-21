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

    public function scopeGetPlayersThatBelongsToOthersTeam($query,$value,$teamId)
    {
        return  $query->whereIn('id', $value)
                      ->when($teamId != null, function($query) use($teamId){
                          return $query->where('team_id', '!=',$teamId);
                      })
                      ->whereNotnull('team_id')
                      ->get();
    }

    public function scopeAssociateTeam($query,$teamId)
    {
        return $query->whereIn('id',request()->players_ids)
                     ->whereNull('team_id')
                     ->update(
                         [
                            'team_id' => $teamId
                         ]
                     );
    }
}
