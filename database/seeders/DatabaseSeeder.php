<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\State;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Artisan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('permissions:sync');
        $permissions = [
            'list only own institutions',
            'list only own courses',
            'view own institution',
            'update own institution',
            'delete own institution',
            'view own course',
            'update own course',
            'delete own course',
        ];
        collect($permissions)->each(fn ($permission) => Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']));

        if (App::isLocal()) {
            Admin::firstOrCreate([
                'name' => 'Esseyed',
                'email' => 'esseyed@gmail.com',
                'tel' => '0000000000',
                'region_name' => 'IPST Ben Arous',
            ], [
                'password' => 'admin',
            ]);
        }

        $admin = Admin::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'tel' => '0000000000',
            'region_name' => 'All',
        ], [
            'password' => 'admin',
        ]);
        $super = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $admin->assignRole($super);

        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'admin']);
        $managerRole->givePermissionTo('create course', 'list only own courses', 'update course', 'view-any course', 'view course');
        $managerRole->givePermissionTo('create user', 'update user', 'view-any user');
        $managerRole->givePermissionTo('create institution', 'update own institution', 'delete own institution', 'list only own institutions', 'view-any institution');

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
            State::firstOrCreate($state);
        }
    }
}
