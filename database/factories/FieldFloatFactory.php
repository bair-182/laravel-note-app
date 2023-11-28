<?php

namespace Database\Factories;

use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldFloatFactory extends Factory
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
            'value' => $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),
            'field_id' => Field::factory()
        ];
    }
}
