<?php

namespace Tests\Unit\Team;

use Tests\TestCase;
use App\Models\Team;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeamModelTest extends TestCase
{
    use DatabaseMigrations;

    private $team;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->team = Team::first();
        $this->team->update(['name' => 'test']);
    }

    public function test_get_all_teams_with_players()
    {
        $teams  = Team::getAllTeamsWithPlayers($this->team->name);

        $this->assertEquals(count($teams),1);
        $this->assertEquals($teams->first()->name,$this->team->name);

        $teams  = Team::getAllTeamsWithPlayers();
        $this->assertEquals(count($teams), count(Team::all()));
    }
  
}
