<?php

namespace Tests\Feature\Ranking;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\MatchTeam;
use App\Models\CardMatch;
use App\Http\Resources\Ranking\RankingResourceCollection;
class RankingControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $token;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->withExceptionHandling();
        $response = $this->post('/api/login', [
            'email'    => 'email@example.com', 
            'password' => 'password'
        ])->assertStatus(200);
        $this->token = $response['token'];
    }

    public function test_it_should_be_able_to_list_ranking_of_teams()
    {
        $rankingTeams = new RankingResourceCollection(MatchTeam::getRankingTeams());
        $response     = $this->get('/api/ranking-teams', ['Authorization' => "Bearer ".$this->token])
                             ->assertStatus(200);
        $this->assertEquals($rankingTeams->response()->getData(true)['data'],$response['ranking']);
    }

    public function test_it_should_be_able_to_list_ranking_of_players()
    {
        $rankingTeams = new RankingResourceCollection(CardMatch::getRankingPlayers());
        $response     = $this->get('/api/ranking-players', ['Authorization' => "Bearer ".$this->token])
                             ->assertStatus(200);
        $this->assertEquals($rankingTeams->response()->getData(true)['data'],$response['ranking']);
    }
    
    
}
