<?php

namespace Tests\Feature\Players;

use Tests\TestCase;
use App\Http\Resources\PlayerResourceCollection;
use App\Models\Player;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class PlayerControllerTest extends TestCase
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

        $randNumber = rand ( 10000000000 , 99999999999 );
        Player::first()->update(['document' => $randNumber]);
    }
    
    public function test_it_shoul_be_able_to_list_all_players()
    {
        $allPlayers = new PlayerResourceCollection(Player::all()->load('team'));
        $response   = $this->get('/api/players', ['Authorization' => "Bearer ".$this->token])
                           ->assertStatus(200);
        $this->assertEquals($allPlayers->response()->getData(true)['data'],$response['players']);
        $this->assertEquals(count($response['players']), Player::all()->count());
    }

    public function test_it_should_be_able_to_validate_fields_to_create_a_player()
    {
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/players')
             ->assertStatus(422)
             ->assertExactJson([
                            'name'         => ['The name field is required.'],
                            'document'     => ['The document field is required.'],
                            'shirt_number' => ['The shirt number field is required.'],
                          ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/players',[
                                            'name'         => ['test'],
                                            'document'     => Player::first()->document, 
                                            'shirt_number' => ['test'],

                                        ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'name'         => ['The name must be a string.'],
                                    'document'     => ['The document has already been taken.'],
                                    'shirt_number' => ['The shirt number must be an integer.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/players',[
                                                'name'         => 'test',
                                                'document'     => ['test'], 
                                                'shirt_number' => 123,
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'document' => ['The document must be an integer.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/players',[
                                                'name'         => 'test',
                                                'document'     => rand(99,9999), 
                                                'shirt_number' => 123,
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'document' => ['The document must be 11 digits.'],
                              ]);

    }

    public function test_it_should_be_able_to_able_to_create_a_player()
    {
        $randNumber = rand ( 10000000000 , 99999999999 );
        $response   = $this->withHeaders(['Authorization' => "Bearer ".$this->token])
                           ->json('POST', '/api/players',[
                                                              'name'         => 'test',
                                                              'document'     => $randNumber,
                                                              'shirt_number' => 12,
                                                         ])
                           ->assertStatus(201);

        $response->assertExactJson([
                                        'name'         => 'test',
                                        'document'     => $randNumber,
                                        'shirt_number' => 12,
                                        'id'           => $response['id']
                                  ]);
    }

    public function test_it_should_be_able_to_validate_fields_to_update_a_player()
    {
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/players/1')
             ->assertStatus(422)
             ->assertExactJson([
                            'name'         => ['The name field is required.'],
                            'document'     => ['The document field is required.'],
                            'shirt_number' => ['The shirt number field is required.'],
                          ]);
                 
        $randNumber = rand ( 10000000000 , 99999999999 );
        Player::all()->where('id','!=',1)->random()->update(['document' => $randNumber]);
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/players/1',[
                                            'name'         => ['test'],
                                            'document'     => $randNumber, 
                                            'shirt_number' => ['test'],

                                        ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'name'         => ['The name must be a string.'],
                                    'document'     => ['The document has already been taken.'],
                                    'shirt_number' => ['The shirt number must be an integer.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/players/1',[
                                                'name'         => 'test',
                                                'document'     => ['test'], 
                                                'shirt_number' => 123,
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'document' => ['The document must be an integer.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/players/1',[
                                                'name'         => 'test',
                                                'document'     => rand(99,9999), 
                                                'shirt_number' => 123,
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'document' => ['The document must be 11 digits.'],
                              ]);
    }

    public function test_it_should_be_able_to_update_a_player()
    {
        $firstPlayer = Player::first();
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/players/1',[
                                                'name'         => 'test 2',
                                                'document'     => $firstPlayer->document,
                                                'shirt_number' => 14,
                                           ])
             ->assertStatus(200)
             ->assertExactJson([
                          'name'         => 'test 2',
                          'document'     => $firstPlayer->document,
                          'shirt_number' => 14,
                          'team'         => $firstPlayer->team,
                          'id'           => 1
                    ]);
    }

}
