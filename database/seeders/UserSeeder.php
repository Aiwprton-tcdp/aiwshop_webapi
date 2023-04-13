<?php

namespace Database\Seeders;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        if (\App\Models\User::first() == null) {
            \App\Models\User::factory()->create([
                'name' => \config('services.admin_seed.name'),
                // 'email' => \config('services.admin_seed.email'),
                'role_id' => 2,// \App\Models\Role::find(2),
                'email_verified_at' => now(),
                'password' => Hash::make(\config('services.admin_seed.password')),
                'remember_token' => Str::random(10),
            ]); //admin
        }
        
        \App\Models\User::factory()->count(50)->create();
    }
}
