<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'created_at' => $this->faker->dateTimeThisYear(),
            'title' => $this->faker->word(),
            'type' => $this->faker->randomElement(['string', 'integer', 'float', 'boolean']),
            'note_id' => $this->faker->numberBetween(1, 5)
        ];
    }
}
