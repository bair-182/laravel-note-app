<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\FieldBool;
use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        \App\Models\User::factory(5)->create();

        $this->call([
            UserSeeder::class,
        ]);
    }
}
