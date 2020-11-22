<?php

namespace Tests\Feature\Teams;

use Tests\TestCase;
use App\Http\Resources\Teams\TeamResourceCollection;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class TeamControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $token;
    private $team;
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
        $this->team     = Team::first();
        $this->team->update(['name' => 'test']);
    }
    
    public function test_it_shoul_be_able_to_list_all_teams()
    {
        $allTeams = new TeamResourceCollection(Team::getAllTeamsWithPlayers());
        $response = $this->get('/api/teams', ['Authorization' => "Bearer ".$this->token])
                         ->assertStatus(200);
        $this->assertEquals($allTeams->response()->getData(true)['data'],$response['teams']);
        $this->assertEquals(count($response['teams']), Team::all()->count());
    }

    public function test_it_shoul_be_able_to_search_a_team()
    {
        
        $allTeams = new TeamResourceCollection(Team::getAllTeamsWithPlayers($this->team->name));
        $response = $this->get('/api/teams?name='.$this->team->name, ['Authorization' => "Bearer ".$this->token])
                         ->assertStatus(200);
        $this->assertEquals($allTeams->response()->getData(true)['data'],$response['teams']);
        $this->assertEquals(count($response['teams']), 1);
    }

    public function test_it_should_be_able_to_validate_fields_to_create_a_team()
    {
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/teams')
             ->assertStatus(422)
             ->assertExactJson([
                            'name'         => ['The name field is required.'],
                          ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/teams',[
                                            'name'        => ['test'],
                                            'players_ids' => 'test',
                                        ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'name'         => ['The name must be a string.'],
                                    'players_ids'  => ['The players ids must be an array.'],
                              ]);
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/teams',[
                                            'name'        => 'test',
                                            'players_ids' => [['test'],['test']],
                                        ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'players_ids'  => [
                                        ['The players ids value must be integer.'],
                                        ['The players ids value must be integer.'],
                                    ],
                              ]);
 
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/teams',[
                                            'name'        => 'test',
                                            'players_ids' => [1,2],
                                        ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'players_ids'  => 
                                        ['Players that belong to another teams are not allowed to be choosen.'],
                              ]);
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/teams',[
                                            'name'        => 'test',
                                            'players_ids' => [1,2,3,4,5,6],
                                        ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'players_ids'  => 
                                        ['The players ids may not have more than 5 items.'],
                              ]);
    }

    public function test_it_should_be_able_to_able_to_create_a_team()
    {
        Player::whereIn('id',[1,2])->update(['team_id' => null]);
        $response   = $this->withHeaders(['Authorization' => "Bearer ".$this->token])
                           ->json('POST', '/api/teams',[
                                                              'name'        => 'test',
                                                              'players_ids' => [1,2],
                                                         ])
                           ->assertStatus(201);
        $response->assertExactJson([
                                        'name'    => 'test',
                                        'players' => Team::find($response['id'])->players->toArray(),
                                        'id'      => $response['id']
                                  ]);

        $this->assertEquals(Player::find(1)->team_id, $response['id']);
        $this->assertEquals(Player::find(2)->team_id, $response['id']);
    }

    public function test_it_should_be_able_to_validate_fields_to_update_a_team()
    {
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/teams/1')
             ->assertStatus(422)
             ->assertExactJson([
                            'name'         => ['The name field is required.'],
                          ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/teams/1',[
                                            'name'        => ['test'],
                                            'players_ids' => 'test',
                                        ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'name'         => ['The name must be a string.'],
                                    'players_ids'  => ['The players ids must be an array.'],
                              ]);
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/teams/1',[
                                            'name'        => 'test',
                                            'players_ids' => [['test'],['test']],
                                        ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'players_ids'  => [
                                        ['The players ids value must be integer.'],
                                        ['The players ids value must be integer.'],
                                    ],
                              ]);
 
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/teams/1',[
                                            'name'        => 'test',
                                            'players_ids' => [1,2],
                                        ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'players_ids'  => 
                                        ['Players that belong to another teams are not allowed to be choosen.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/teams/1',[
                                            'name'        => 'test',
                                            'players_ids' => [1,2,3,4,5,6],
                                        ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'players_ids'  => 
                                        ['The players ids may not have more than 5 items.'],
                              ]);

        Player::whereIn('id',[1,2,3,4,5])->update(['team_id' => 1]);
        Player::find(6)->update(['team_id' => NULL]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/teams/1',[
                                            'name'        => 'test',
                                            'players_ids' => [6],
                                        ])
             ->assertStatus(422)
             ->assertExactJson([
                                    "max_number_of_players" => "The number max of players in the time is five.",
                              ]);
    }

    public function test_it_should_be_able_update_a_team()
    {
        Player::whereIn('id',[1,2])->update(['team_id' => 1]);
        Player::whereNotIn('id',[1,2])->update(['team_id' => NULL]);
        $response   = $this->withHeaders(['Authorization' => "Bearer ".$this->token])
                           ->json('PUT', '/api/teams/1',[
                                                              'name'        => 'test',
                                                              'players_ids' => [3],
                                                         ])
                           ->assertStatus(200);
        $response->assertExactJson([
                                        'name'    => 'test',
                                        'players' => Team::find($response['id'])->players->toArray(),
                                        'id'      => $response['id']
                                  ]);

        $this->assertEquals(Player::find(1)->team_id, $response['id']);
        $this->assertEquals(Player::find(2)->team_id, $response['id']);
        $this->assertEquals(Player::find(3)->team_id, $response['id']);
    }

}
