<?php

namespace Tests\Feature\Matches;

use Tests\TestCase;
use App\Models\match;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MatchControllerTest extends TestCase
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

    public function test_it_should_be_able_to_validate_fields_to_create_a_match()
    {
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/matches')
             ->assertStatus(422)
             ->assertExactJson([
                                    'date'          => ['The date field is required.'],
                                    'end_time'      => ['The end time field is required.'],
                                    'match_results' => ['The match results field is required.'],
                                    'start_time'    => ['The start time field is required.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/matches',[
                                                'date' => '2020-10-10'   
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'date'          => ['The date does not match the format d/m/Y.'],
                                    'end_time'      => ['The end time field is required.'],
                                    'match_results' => ['The match results field is required.'],
                                    'start_time'    => ['The start time field is required.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/matches',[
                                                'date'       => '10/10/2020', 
                                                'end_time'   => '123',
                                                'start_time' => '123'
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'end_time'      => ['The end time does not match the format H:i.'],
                                    'start_time'    => ['The start time does not match the format H:i.'],
                                    'match_results' => ['The match results field is required.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/matches',[
                                                'date'       => '10/10/2020', 
                                                'start_time' => '10:10',
                                                'end_time'   => '10:09',
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'end_time'      => ['The end time must be a date after start time.'],
                                    'match_results' => ['The match results field is required.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/matches',[
                                                'date'          => '10/10/2020', 
                                                'start_time'    => '10:10',
                                                'end_time'      => '10:11',
                                                'match_results' => 'test'
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'match_results' => ['The match results must be an array.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/matches',[
                                                'date'          => '10/10/2020', 
                                                'start_time'    => '10:10',
                                                'end_time'      => '10:11',
                                                'match_results' => [
                                                    [
                                                        'team_id' => 1,
                                                        'gols'    => 2,
                                                    ],
                                                    [
                                                        'team_id' => 2,
                                                        'gols'    => 2,
                                                    ],
                                                    [
                                                        'team_id' => 3,
                                                        'gols'    => 2,
                                                    ]
                                                ]
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'match_results' => ['The match results may not have more than 2 items.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/matches',[
                                                'date'          => '10/10/2020', 
                                                'start_time'    => '10:10',
                                                'end_time'      => '10:11',
                                                'match_results' => [
                                                    [
                                                        'team_id' => 1,
                                                        'gols'    => 2,
                                                    ],
                                                    [
                                                        'team_id' => 1,
                                                        'gols'    => 2,
                                                    ],
                                                ]
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'match_results' => [
                                            [
                                                'team_id' => ['The team id value needs to be unique.']
                                            ],
                                            [
                                                'team_id' => ['The team id value needs to be unique.']
                                            ]
                                        ],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/matches',[
                                                'date'          => '10/10/2020', 
                                                'start_time'    => '10:10',
                                                'end_time'      => '10:11',
                                                'match_results' => [
                                                    [
                                                        'team_id' => 2,
                                                        'gols'    => ['1'],
                                                    ],
                                                    [
                                                        'team_id' => 1,
                                                        'gols'    => ['1'],
                                                    ],
                                                ]
                                      ])
        ->assertStatus(422)
        ->assertExactJson([
                               'match_results' => [
                                       [
                                           'gols' => ['The gols value must be integer.']
                                       ],
                                       [
                                           'gols' => ['The gols value must be integer.']
                                       ]
                                   ],
                         ]);
                         
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/matches',[
                                                'date'          => '10/10/2020', 
                                                'start_time'    => '10:10',
                                                'end_time'      => '10:11',
                                                'match_results' => [
                                                    [
                                                        'team_id' => 2,
                                                        'gols'    => 3,
                                                    ],
                                                    [
                                                        'team_id' => 1,
                                                        'gols'    => 2,
                                                    ],
                                                ], 
                                                'match_cards' => 'test'
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'match_cards' => ['The match cards must be an array.'],
                              ]);
                              
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('POST', '/api/matches',[
                                                'date'          => '10/10/2020', 
                                                'start_time'    => '10:10',
                                                'end_time'      => '10:11',
                                                'match_results' => [
                                                    [
                                                        'team_id' => 2,
                                                        'gols'    => 3,
                                                    ],
                                                    [
                                                        'team_id' => 1,
                                                        'gols'    => 2,
                                                    ],
                                                ], 
                                                'match_cards' => [
                                                    [
                                                        'player_id'  => ['2'],
                                                        'card_id'    => ['1'],
                                                    ],
                                                ]
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'match_cards' => [
                                        [
                                            'card_id'   => ['The card id value must be integer.'],
                                            'player_id' => ['The player id value must be integer.']
                                        ],
                                    ],
                              ]);  
    }

    public function test_it_should_be_able_to_create_a_match()
    {
        $response   = $this->withHeaders(['Authorization' => "Bearer ".$this->token])
                           ->json('POST', '/api/matches',[
                                                            "date"          => "10/10/2020", 
                                                            "start_time"    => "20:20", 
                                                            "end_time"      => "20:30", 
                                                            "match_results" => [
                                                                [
                                                                    "team_id" => 1, 
                                                                    "gols"    => 2
                                                                ],
                                                                [
                                                                    "team_id" => 2, 
                                                                    "gols"    => 4
                                                                ]
                                                            ],
                                                            "match_cards" => [
                                                                [
                                                                    "card_id"   => 1,
                                                                    "player_id" => 1
                                                                ], 
                                                                [
                                                                    "card_id"   => 2,
                                                                    "player_id" => 1
                                                                ]
                                                            ]
                                                         ])
                           ->assertStatus(201);

        $match = Match::find($response['id']);
        $response->assertExactJson([
                                    'date'       => '2020-10-10',
                                    'start_time' => '20:20',
                                    'end_time'   => '20:30',
                                    'id'         => $response['id'],
                                    'teams'      => $match->teams->toArray(),
                                    'cards'      => $match->cards->toArray(),
                             ]);
    }


    public function test_it_should_be_able_to_validate_fields_to_update_a_match()
    {
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/matches/1')
             ->assertStatus(422)
             ->assertExactJson([
                                    'date'          => ['The date field is required.'],
                                    'end_time'      => ['The end time field is required.'],
                                    'match_results' => ['The match results field is required.'],
                                    'start_time'    => ['The start time field is required.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/matches/1',[
                                                'date' => '2020-10-10'   
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'date'          => ['The date does not match the format d/m/Y.'],
                                    'end_time'      => ['The end time field is required.'],
                                    'match_results' => ['The match results field is required.'],
                                    'start_time'    => ['The start time field is required.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/matches/1',[
                                                'date'       => '10/10/2020', 
                                                'end_time'   => '123',
                                                'start_time' => '123'
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'end_time'      => ['The end time does not match the format H:i.'],
                                    'start_time'    => ['The start time does not match the format H:i.'],
                                    'match_results' => ['The match results field is required.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/matches/1',[
                                                'date'       => '10/10/2020', 
                                                'start_time' => '10:10',
                                                'end_time'   => '10:09',
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'end_time'      => ['The end time must be a date after start time.'],
                                    'match_results' => ['The match results field is required.'],
                              ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/matches/1',[
                                                'date'          => '10/10/2020', 
                                                'start_time'    => '10:10',
                                                'end_time'      => '10:11',
                                                'match_results' => 'test'
                                           ])
             ->assertStatus(422)
             ->assertExactJson([
                                    'match_results' => ['The match results must be an array.'],
                              ]);
             
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/matches/1',[
                                                'date'          => '10/10/2020', 
                                                'start_time'    => '10:10',
                                                'end_time'      => '10:11',
                                                'match_results' => [
                                                    [
                                                        'team_id' => 1,
                                                        'gols'    => 2,
                                                    ],
                                                    [
                                                        'team_id' => 2,
                                                        'gols'    => 2,
                                                    ],
                                                    [
                                                        'team_id' => 3,
                                                        'gols'    => 2,
                                                    ]
                                            ]
                                      ])
            ->assertStatus(422)
            ->assertExactJson([
                                   'match_results' => ['The match results may not have more than 2 items.'],
                             ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/matches/1',[
                                           'date'          => '10/10/2020', 
                                           'start_time'    => '10:10',
                                           'end_time'      => '10:11',
                                           'match_results' => [
                                               [
                                                   'team_id' => 1,
                                                   'gols'    => 2,
                                               ],
                                               [
                                                   'team_id' => 1,
                                                   'gols'    => 2,
                                               ],
                                           ]
                                      ])
            ->assertStatus(422)
            ->assertExactJson([
                                   'match_results' => [
                                           [
                                               'team_id' => ['The team id value needs to be unique.']
                                           ],
                                           [
                                               'team_id' => ['The team id value needs to be unique.']
                                           ]
                                    ],
                             ]);

        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/matches/1',[
                                                'date'          => '10/10/2020', 
                                                'start_time'    => '10:10',
                                                'end_time'      => '10:11',
                                                'match_results' => [
                                                    [
                                                        'team_id' => 2,
                                                        'gols'    => ['1'],
                                                    ],
                                                    [
                                                        'team_id' => 1,
                                                        'gols'    => ['1'],
                                                    ],
                                           ]
                                 ])
            ->assertStatus(422)
            ->assertExactJson([
                                   'match_results' => [
                                           [
                                               'gols' => ['The gols value must be integer.']
                                           ],
                                           [
                                               'gols' => ['The gols value must be integer.']
                                           ]
                                       ],
                             ]);
                    
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/matches/1',[
                                                'date'          => '10/10/2020', 
                                                'start_time'    => '10:10',
                                                'end_time'      => '10:11',
                                                'match_results' => [
                                                    [
                                                        'team_id' => 2,
                                                        'gols'    => 3,
                                                    ],
                                                    [
                                                        'team_id' => 1,
                                                        'gols'    => 2,
                                                    ],
                                                ], 
                                                'match_cards' => 'test'
                                          ])
            ->assertStatus(422)
            ->assertExactJson([
                                   'match_cards' => ['The match cards must be an array.'],
                             ]);
                         
        $this->withHeaders(['Authorization' => "Bearer ".$this->token])
             ->json('PUT', '/api/matches/1',[
                                                'date'          => '10/10/2020', 
                                                'start_time'    => '10:10',
                                                'end_time'      => '10:11',
                                                'match_results' => [
                                                    [
                                                        'team_id' => 2,
                                                        'gols'    => 3,
                                                    ],
                                                    [
                                                        'team_id' => 1,
                                                        'gols'    => 2,
                                                    ],
                                                ], 
                                                'match_cards' => [
                                                    [
                                                        'player_id'  => ['2'],
                                                        'card_id'    => ['1'],
                                                    ],
                                                ]
                                           ])
            ->assertStatus(422)
            ->assertExactJson([
                                   'match_cards' => [
                                       [
                                           'card_id' => ['The card id value must be integer.'],
                                           'player_id' => ['The player id value must be integer.']
                                       ],
                                   ],
                             ]); 
    }

    public function test_it_should_be_able_to_able_to_update_a_match()
    {
        $response   = $this->withHeaders(['Authorization' => "Bearer ".$this->token])
                           ->json('PUT', '/api/matches/1',[
                                                            "date"          => "10/12/2020", 
                                                            "start_time"    => "20:30", 
                                                            "end_time"      => "20:40", 
                                                            "match_results" => [
                                                                [
                                                                    "team_id" => 1, 
                                                                    "gols"    => 1
                                                                ],
                                                                [
                                                                    "team_id" => 2, 
                                                                    "gols"    => 2
                                                                ]
                                                            ],
                                                            "match_cards" => [
                                                                [
                                                                    "card_id"   => 2,
                                                                    "player_id" => 1
                                                                ], 
                                                                [
                                                                    "card_id"   => 1,
                                                                    "player_id" => 1
                                                                ]
                                                            ]
                                                         ])
                           ->assertStatus(200);

        $match = Match::find($response['id']);
        $response->assertExactJson([
                                    'date'       => '2020-12-10',
                                    'start_time' => '20:30',
                                    'end_time'   => '20:40',
                                    'id'         => $response['id'],
                                    'teams'      => $match->teams->toArray(),
                                    'cards'      => $match->cards->toArray(),
                             ]);
    }
}
