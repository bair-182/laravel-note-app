<?php

namespace Database\Factories;

use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldIntFactory extends Factory
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
            'value' => $this->faker->numberBetween($min = 100, $max = 9000),
            'field_id' => Field::factory()
        ];
    }
}
