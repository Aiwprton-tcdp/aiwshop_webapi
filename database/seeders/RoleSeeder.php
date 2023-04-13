<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['user', 'admin', 'moderator', 'banned', 'chat_banned'];
        $c = Role::count();

        for ($i = $c; $i < count($names); $i++) {
            Role::create(['name' => $names[$i]]);
        }
    }
}
