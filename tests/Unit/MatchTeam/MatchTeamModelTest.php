<?php

namespace Tests\Unit\MatchTeam;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Team;
use App\Models\MatchTeam;

class MatchTeamModelTest extends TestCase
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

    public function test_get_ranking_teams()
    {
        
        $matchTeams = MatchTeam::getRankingTeams($this->team->name);
        $this->assertEquals(count($matchTeams),1);
        $this->assertEquals($matchTeams->first()->name,$this->team->name);

        $matchTeams = MatchTeam::getRankingTeams();
        $this->assertEquals(count($matchTeams), count(
                                                        MatchTeam::groupBy('team_id')
                                                                  ->selectRaw('SUM(gols) as gols')
                                                                  ->get()
                                                     )
                            );
        $this->assertEquals($matchTeams->first()->ranking_position,1);

    }
}
