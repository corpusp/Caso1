<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Admin' ]);
        $role2 = Role::create(['name' => 'Cliente'] );

        Permission::create(['name' => 'mvp',])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'users',])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'deleteUser',])->assignRole([$role2]);
        Permission::create(['name' => 'addUSer',])->assignRole([$role1]);



    }
}
