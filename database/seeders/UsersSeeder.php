<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@alijumaan.com',
            'mobile' => '966500000001',
            'role_id' => 1,
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('admin'),
            'status' => 1,
        ]);



        User::create([
            'name' => 'Editor',
            'username' => 'editor',
            'email' => 'editor@alijumaan.com',
            'mobile' => '966500000002',
            'role_id' => 2,
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('editor'),
            'status' => 1,
        ]);



        User::create([
            'name' => 'Ali Jumaan',
            'username' => 'ali', 'email' => 'ali@alijumaan.com',
            'mobile' => '966500000003',
            'role_id' => 3,
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('ali'),
            'status' => 1,
        ]);
    }
}
