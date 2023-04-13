<?php

namespace Database\Seeders;

class DatabaseSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            GoodSeeder::class,
            SaleSeeder::class,
            SocialSeeder::class,
            UsersSocialSeeder::class,
        ]);
    }
}
