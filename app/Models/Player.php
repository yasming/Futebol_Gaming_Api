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
    protected $with = ['team'];

    protected $hidden = [
        'updated_at',
        'created_at',
        'team_id'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
