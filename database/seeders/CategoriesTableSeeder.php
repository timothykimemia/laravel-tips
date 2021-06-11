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
        Category::create(['name' => 'PHP', 'status' => 1]);
        Category::create(['name' => 'Laravel', 'status' => 1]);
        Category::create(['name' => 'JavaScript', 'status' => 1]);
        Category::create(['name' => 'VueJs', 'status' => 1]);

    }
}
