<?php

namespace Tests\Unit\CardMatch;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Player;
use App\Models\CardMatch;

class CardMatchModelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_get_ranking_of_players()
    {
        $player       = Player::first();
        $cardMatches  = CardMatch::getRankingPlayers($player->name);

        $this->assertEquals(count($cardMatches),1);
        $this->assertEquals($cardMatches->first()->name,$player->name);

        $cardMatches = CardMatch::getRankingPlayers();
        $this->assertEquals(count($cardMatches), count(
                                                        CardMatch::groupBy('player_id')
                                                                 ->join('cards','cards.id','card_match.card_id')
                                                                 ->join('players','players.id','card_match.player_id')
                                                                 ->selectRaw('SUM(cards.points)')
                                                                 ->get()
                                                     )
                            );
        $this->assertEquals($cardMatches->first()->ranking_position,1);

    }
  
}
