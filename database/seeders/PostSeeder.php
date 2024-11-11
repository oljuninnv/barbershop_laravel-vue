<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    public function run()
    {
        $posts = [
            ['name' => 'Admin'],
            ['name' => 'Barber'],
            ['name' => 'Staff'],
            ['name' => 'Undefined'],
        ];

        DB::table('posts')->insert($posts);
    }
}
