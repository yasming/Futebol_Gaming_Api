<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    const MAX_NUMBER_OF_PLAYERS         = 5;
    const MESSAGE_MAX_NUMBER_OF_PLAYERS = 'The number max of players in the time is five.';
    protected $fillable = [
        'name'
    ];
    
    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
