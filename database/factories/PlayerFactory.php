<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlayerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Player::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'         => $this->faker->name,
            'document'     => rand(1, 10000),
            'shirt_number' => rand(1,99),
            'team_id'      => $this->faker->numberBetween(1, 20)
        ];
    }
}
