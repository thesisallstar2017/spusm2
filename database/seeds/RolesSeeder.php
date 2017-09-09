<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles  = [
          \App\Models\Role::ADMIN, \App\Models\Role::FACULTY, \App\Models\Role::STUDENT
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create([
              'name'  => $role,
              'display_name'  => $role
            ]);
        }
    }
}
