<?php

namespace Database\Seeders;

use App\Models\Page;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        Page::create([
            'title'         => 'About Us',
            'description'   => 'Let’s get this conversation started. Tell us a bit about yourself, and we’ll get in touch as soon as we can.',
            'status'        => 1,
            'comment_able'  => 0,
            'post_type'     => 'page',
            'user_id'       => 1,
            'category_id'   => 1,
        ]);

        Page::create([
            'title'         => 'Our Vision',
            'description'   => 'Our vision is to help people in different Programming fields. We believe ALLAH cares deeply for the marginalized in society and our mission is to join in with Him to help people flourish socially, economically, and spiritually.',
            'status'        => 1,
            'comment_able'  => 0,
            'post_type'     => 'page',
            'user_id'       => 1,
            'category_id'   => 1,
        ]);



    }
}
