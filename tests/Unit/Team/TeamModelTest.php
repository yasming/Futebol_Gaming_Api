<?php

namespace Tests\Unit\Team;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Team;

class TeamModelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    
    public function test_get_all_teams_with_players()
    {
        $team   = Team::first();
        $teams  = Team::getAllTeamsWithPlayers($team->name);

        $this->assertEquals(count($teams),1);
        $this->assertEquals($teams->first()->name,$team->name);

        $teams  = Team::getAllTeamsWithPlayers();
        $this->assertEquals(count($teams), count(Team::all()));
    }
  
}
