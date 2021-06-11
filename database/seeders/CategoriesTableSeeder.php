<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => 'General', 'status' => 1]);
        Category::create(['name' => 'Apache', 'status' => 1]);
        Category::create(['name' => 'Nginx', 'status' => 1]);
        Category::create(['name' => 'Design', 'status' => 1]);
        Category::create(['name' => 'Programing', 'status' => 1]);

    }
}
