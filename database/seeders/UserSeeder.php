<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\FieldBool;
use App\Models\FieldFloat;
use App\Models\FieldInt;
use App\Models\FieldString;
use App\Models\Note;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        \App\Models\User::factory(3)
//            ->create()->each(function ($notes) {
//                $notes->notes()->saveMany(
//                    Note::factory(rand(1, 2))->make());
//            });
        \App\Models\User::factory(4)
            ->has(factory: Note::factory()->count(rand(1,3))
                ->has(Field::factory()->count(rand(1,3))
                    ->has(FieldBool::factory()->count(rand(0,3)))
                    ->has(FieldString::factory()->count(rand(0,3)))
                    ->has(FieldInt::factory()->count(rand(0,3)))
                    ->has(FieldFloat::factory()->count(rand(0,3)))
            ))
            ->create();
    }
}
