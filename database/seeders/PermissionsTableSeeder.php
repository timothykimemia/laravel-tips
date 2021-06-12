<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('permissions')->truncate();

        DB::table('permissions')->insert(['name' => 'view-category', 'description' => 'view category', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'add-category', 'description' => 'add category', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'edit-category', 'description' => 'edit category', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'delete-category', 'description' => 'delete category', 'message' => 'Forbidden']);

        DB::table('permissions')->insert(['name' => 'view-post', 'description' => 'view post', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'add-post', 'description' => 'add post', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'edit-post', 'description' => 'edit post', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'delete-post', 'description' => 'delete post', 'message' => 'Forbidden']);

        DB::table('permissions')->insert(['name' => 'view-comment', 'description' => 'view comment', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'add-comment', 'description' => 'add comment', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'edit-comment', 'description' => 'edit comment', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'delete-comment', 'description' => 'delete comment', 'message' => 'Forbidden']);

        DB::table('permissions')->insert(['name' => 'view-contact', 'description' => 'view contact', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'add-contact', 'description' => 'add contact', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'edit-contact', 'description' => 'edit contact', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'delete-contact', 'description' => 'delete contact', 'message' => 'Forbidden']);

        DB::table('permissions')->insert(['name' => 'view-page', 'description' => 'view page', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'add-page', 'description' => 'add page', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'edit-page', 'description' => 'edit page', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'delete-page', 'description' => 'delete page', 'message' => 'Forbidden']);

        DB::table('permissions')->insert(['name' => 'view-role', 'description' => 'view role', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'add-role', 'description' => 'add role', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'edit-role', 'description' => 'edit role', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'delete-role', 'description' => 'delete role', 'message' => 'Forbidden']);

        DB::table('permissions')->insert(['name' => 'view-user', 'description' => 'view user', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'add-user', 'description' => 'add user', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'edit-user', 'description' => 'edit user', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'delete-user', 'description' => 'delete user', 'message' => 'Forbidden']);

        DB::table('permissions')->insert(['name' => 'view-tag', 'description' => 'view tag', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'add-tag', 'description' => 'add tag', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'edit-tag', 'description' => 'edit tag', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'delete-tag', 'description' => 'delete tag', 'message' => 'Forbidden']);

        DB::table('permissions')->insert(['name' => 'view-setting', 'description' => 'view setting', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'add-setting', 'description' => 'add setting', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'edit-setting', 'description' => 'edit setting', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'delete-setting', 'description' => 'delete setting', 'message' => 'Forbidden']);

        DB::table('permissions')->insert(['name' => 'view-supervisor', 'description' => 'view supervisor', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'add-supervisor', 'description' => 'add supervisor', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'edit-supervisor', 'description' => 'edit supervisor', 'message' => 'Forbidden']);
        DB::table('permissions')->insert(['name' => 'delete-supervisor', 'description' => 'delete supervisor', 'message' => 'Forbidden']);

    }
}
