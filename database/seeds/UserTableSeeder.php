<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::where('name', 'admin')->first()->id;
        $faculty_role  = Role::where('name', 'faculty')->first()->id;
        $student_role    = Role::where('name', 'student')->first()->id;

        $admin  = User::create([
          'name'  => 'Admin',
          'user_id' => '10001',
          'email' => 'admin@gmail.com',
          'password'  => bcrypt('password')
        ]);

        $admin->roles()->attach($admin_role);

        $faculty   = User::create([
          'name'  => 'Faculty',
          'user_id' => '10002',
          'email' => 'faculty@gmail.com',
          'password'   => bcrypt('password')
        ]);

        $faculty->roles()->attach($faculty_role);

        $student   = User::create([
          'name'  => 'Student',
          'user_id' => '10003',
          'email' => 'student@gmail.com',
          'password'   => bcrypt('password')
        ]);

        $student->roles()->attach($student_role);
    }
}
