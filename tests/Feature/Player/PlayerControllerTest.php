<?php

namespace Tests\Feature\Player;

use Tests\TestCase;
use App\Http\Resources\PostCollection;
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
        // $this->withExceptionHandling();
        $response = $this->post('/api/login', [
            'email'    => 'email@example.com', 
            'password' => 'password'
        ])->assertStatus(200);
        $this->token = $response['token'];
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

}
