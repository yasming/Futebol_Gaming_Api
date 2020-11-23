<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition()
    {
        return [
            'name'         => 'janedoe'.rand(1,9999),
            'document'     => $this->faker->unique()->numberBetween(1,10000),
            'shirt_number' => rand(1,99),
            'team_id'      => $this->faker->numberBetween(1, 20)
        ];
    }
}
