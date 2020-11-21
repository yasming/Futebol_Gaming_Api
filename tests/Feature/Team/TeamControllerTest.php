<?php

namespace Tests\Feature\Team;

use Tests\TestCase;
use App\Http\Resources\TeamResourceCollection;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class TeamControllerTest extends TestCase
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
    
    public function test_it_shoul_be_able_to_list_all_teams()
    {
        $allTeams = new TeamResourceCollection(Team::all()->load('players'));
        $response   = $this->get('/api/teams', ['Authorization' => "Bearer ".$this->token])
                           ->assertStatus(200);
        $this->assertEquals($allTeams->response()->getData(true)['data'],$response['teams']);
        $this->assertEquals(count($response['teams']), Team::all()->count());
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
                                        ['Each value must be integer'],
                                        ['Each value must be integer'],
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

}
