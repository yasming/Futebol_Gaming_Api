<?php

namespace Tests\Unit\MatchTeam;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
        
    }
  
}
