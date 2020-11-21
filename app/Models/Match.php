<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function cards()
    {
        return $this->belongsToMany(Card::class);
    }
}