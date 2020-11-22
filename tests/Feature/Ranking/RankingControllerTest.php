<?php

namespace Tests\Feature\Ranking;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\MatchTeam;
use App\Models\CardMatch;
use App\Models\Team;
use App\Models\Player;
use App\Http\Resources\Ranking\RankingResourceCollection;
class RankingControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $token;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
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

    public function test_it_shoul_be_able_to_search_a_team_in_the_ranking()
    {
        $team         = Team::first();
        $rankingTeams = new RankingResourceCollection(MatchTeam::getRankingTeams($team->name));
        $response     = $this->get('/api/ranking-teams?name='.$team->name, ['Authorization' => "Bearer ".$this->token])
                             ->assertStatus(200);
        $this->assertEquals($rankingTeams->response()->getData(true)['data'],$response['ranking']);
        $this->assertEquals(count($response['ranking']),1);
    }

    public function test_it_should_be_able_to_list_ranking_of_players()
    {
        $rankingTeams = new RankingResourceCollection(CardMatch::getRankingPlayers());
        $response     = $this->get('/api/ranking-players', ['Authorization' => "Bearer ".$this->token])
                             ->assertStatus(200);
        $this->assertEquals($rankingTeams->response()->getData(true)['data'],$response['ranking']);
    }

    public function test_it_should_be_able_to_search_a_player_in_the_ranking()
    {
        $player       = Player::first();
        $rankingTeams = new RankingResourceCollection(CardMatch::getRankingPlayers($player->name));
        $response     = $this->get('/api/ranking-players?name='.$player->name, ['Authorization' => "Bearer ".$this->token])
                             ->assertStatus(200);
        $this->assertEquals($rankingTeams->response()->getData(true)['data'],$response['ranking']);
        $this->assertEquals(count($response['ranking']),1);
    }    
    
}
