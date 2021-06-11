<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $images = glob(storage_path('app/public/assets/posts/*.*'));
        foreach ($images as $image) {
            unlink($image);
        }

        $this->call(RolesTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(PostsTagsTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);

    }
}
