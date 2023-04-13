<?php

namespace Database\Seeders;

use App\Models\Social;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['mail', 'telegram', 'vkontakte', 'whatsapp'];
        $c = Social::count();

        for ($i = $c; $i < count($names); $i++) {
            Social::create(['name' => $names[$i]]);
        }
    }
}
