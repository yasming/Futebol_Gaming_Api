<?php
namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class MatchTeamCollection extends Collection
{
    public function addRankingPosition()
    {
        return  $this->map(function ($item,$index) {
                    $item['ranking_position'] = $index+1;
                    return $item;
                });
    }
}