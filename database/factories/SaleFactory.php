<?php

namespace Database\Factories;

use App\Models\Good;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $goods = Good::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();
        return [
            'good' => fake()->randomElement($goods),
            'buyer' => fake()->randomElement($users),
            'price' => fake()->randomFloat(2, 1, 100),
            'discount' => fake()->randomDigit(),
            // 'is_returned' => false,
        ];
    }
}
