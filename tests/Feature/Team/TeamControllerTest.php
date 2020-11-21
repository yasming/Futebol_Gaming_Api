<?php

namespace Tests\Feature\Team;

use Tests\TestCase;
use App\Http\Resources\TeamResourceCollection;
use App\Models\Team;
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

}
