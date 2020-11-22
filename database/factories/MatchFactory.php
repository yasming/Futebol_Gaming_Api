<?php

namespace Database\Factories;

use App\Models\Match;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class MatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Match::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date'       => Carbon::parse($this->faker->date)->format('d/m/Y'),
            'start_time' => $this->faker->time,
            'end_time'   => $this->faker->time,
        ];
    }
}
