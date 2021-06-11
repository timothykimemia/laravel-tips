<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        DB::table('roles')->insert(['id' => 1, 'name' => 'admin']);

        DB::table('roles')->insert(['id' => 2, 'name' => 'editor']);

        DB::table('roles')->insert(['id' => 3, 'name' => 'user']);
    }
}
