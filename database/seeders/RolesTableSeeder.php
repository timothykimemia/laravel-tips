<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();


        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'System Administrator',
            'allowed_route' => 'admin'
        ]);
        $editorRole = Role::create([
            'name' => 'editor',
            'display_name' => 'Supervisor',
            'description' => 'System Supervisor',
            'allowed_route' => 'admin'
        ]);
        $userRole = Role::create([
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Normal User',
            'allowed_route' => null
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@alijumaan.com',
            'mobile' => '966500000001',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('admin'),
            'status' => 1,
        ]);
        $admin->attachRole($adminRole);


        $editor = User::create([
            'name' => 'Editor',
            'username' => 'editor',
            'email' => 'editor@alijumaan.com',
            'mobile' => '966500000002',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('editor'),
            'status' => 1,
        ]);
        $editor->attachRole($editorRole);


        $user1 = User::create([
            'name' => 'Ali Jumaan',
            'username' => 'ali', 'email' => 'ali@alijumaan.com',
            'mobile' => '966500000003', 'email_verified_at' => Carbon::now(),
            'password' => bcrypt('ali'),
            'status' => 1,
            ]);
        $user1->attachRole($userRole);

    }
}
