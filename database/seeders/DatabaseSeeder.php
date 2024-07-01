<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\State;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (App::isLocal()) {
            Admin::create([
                'name' => 'Esseyed',
                'email' => 'esseyed@gmail.com',
                'tel' => '0000000000',
                'region_name' => 'IPST Ben Arous',
                'password' => 'admin',
                'is_super' => false,
            ]);
        }

        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'tel' => '0000000000',
            'region_name' => 'All',
            'password' => 'admin',
            'is_super' => true,
        ]);
        $super = Role::create(['name' => 'Super Admin']);
        $admin->assignRole($super);

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
