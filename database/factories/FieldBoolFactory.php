<?php

namespace Database\Factories;

use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldBoolFactory extends Factory
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
            'value' => $this->faker->boolean($chanceOfGettingTrue = 50),
            'field_id' => Field::factory()
        ];
    }
}
