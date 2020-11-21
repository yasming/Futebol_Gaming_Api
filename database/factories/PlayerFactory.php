<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition()
    {
        return [
            'name'         => $this->faker->name,
            'document'     => $this->faker->unique()->numberBetween(1,10000),
            'shirt_number' => rand(1,99),
            'team_id'      => $this->faker->numberBetween(1, 20)
        ];
    }
}
