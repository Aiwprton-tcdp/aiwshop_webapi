<?php

namespace Database\Seeders;

class GoodSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        \App\Models\Good::factory(200)->create();
    }
}
