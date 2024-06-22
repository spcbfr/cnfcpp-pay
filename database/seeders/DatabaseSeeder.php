<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $states = [
            ['name' => 'Tunis'],
            ['name' => 'Ariana'],
            ['name' => 'Ben Arous'],
            ['name' => 'Manouba'],
            ['name' => 'Nabeul'],
            ['name' => 'Zaghouan'],
            ['name' => 'Bizerte'],
            ['name' => 'Béja'],
            ['name' => 'Jendouba'],
            ['name' => 'Kef'],
            ['name' => 'Siliana'],
            ['name' => 'Kairouan'],
            ['name' => 'Kasserine'],
            ['name' => 'Sidi Bouzid'],
            ['name' => 'Sousse'],
            ['name' => 'Monastir'],
            ['name' => 'Mahdia'],
            ['name' => 'Sfax'],
            ['name' => 'Gabès'],
            ['name' => 'Medenine'],
            ['name' => 'Tataouine'],
            ['name' => 'Gafsa'],
            ['name' => 'Tozeur'],
            ['name' => 'Kebili'],
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
}
