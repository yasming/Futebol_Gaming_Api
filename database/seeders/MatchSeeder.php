<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Match;

class MatchSeeder extends Seeder
{
    public function run()
    {
        Match::factory()->count(60)->create()->each(function($item){
            $item->teams()->attach(rand(1, 20), 
                [
                    'gols' => rand(1, 10),
                ]);
            $item->cards()->attach(rand(1, 2), 
                [
                    'player_id' => rand(1, 20),
                ]);
      });;

    }
}
