<?php

namespace Tests\Unit\Player;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Player;

class PlayerModelTest extends TestCase
{
    use DatabaseMigrations;

    private $player;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->player = Player::first();
        $this->player->update(['name' => 'test']);
    }
    
    public function test_get_all_players_with_teams()
    {
        $players  = Player::getAllPlayersWithTeam($this->player->name);

        $this->assertEquals(count($players),1);
        $this->assertEquals($players->first()->name,$this->player->name);

        $players  = Player::getAllPlayersWithTeam();
        $this->assertEquals(count($players), count(Player::all()));
    }

    public function test_get_players_that_belongs_to_others_team()
    {
        $players  = Player::getPlayersThatBelongsToOthersTeam([1,2]);

        $this->assertEquals(count($players),2);

        Player::first()->update(['team_id' => 2]);

        $players  = Player::getPlayersThatBelongsToOthersTeam([1,2],2);

        $this->assertEquals(count($players),1);
    }

    public function test_associate_team()
    {
        Player::whereIn('id',[1,2])->update(['team_id' => NULL]);
        Player::whereTeamId(1)->update(['team_id' => NULL]);

        Player::associateTeam(1,[1,2]);
        $this->assertEquals(Player::first()->team_id,1);
        $this->assertEquals(Player::whereId(2)->first()->team_id,1);
    }
  
}
