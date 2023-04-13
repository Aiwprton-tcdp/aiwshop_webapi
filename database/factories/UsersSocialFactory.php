<?php

namespace Database\Factories;

use App\Models\Social;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsersSocial>
 */
class UsersSocialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $users = User::pluck('id')->toArray();
        $socials = Social::pluck('id')->toArray();
        return [
            'user_id' => fake()->randomElement($users),
            'social_id' => fake()->randomElement($socials),
            'value' => fake()->unique()->safeEmail(),
        ];
    }
}
