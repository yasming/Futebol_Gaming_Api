<?php

namespace Tests\Unit\MatchTeam;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Team;
use App\Models\MatchTeam;

class MatchTeamModelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_get_ranking_teams()
    {
        $team       = Team::first();
        $matchTeams = MatchTeam::getRankingTeams($team->name);

        $this->assertEquals(count($matchTeams),1);
        $this->assertEquals($matchTeams->first()->name,$team->name);

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
