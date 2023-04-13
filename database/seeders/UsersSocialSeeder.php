<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\App\Models\UsersSocial::first() == null) {
            \App\Models\UsersSocial::factory()->create([
                'social_id' => 1,
                'value' => \config('services.admin_seed.email'),
                'user_id' => 1,
            ]); //admin
        }
        
        \App\Models\UsersSocial::factory()->count(50)->create();
    }
}
