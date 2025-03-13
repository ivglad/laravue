<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (User::exists()) {
            $user = User::inRandomOrder()->first();
        }
        else {
            $user = User::factory()->create();
        }
        return [
            'is_read' => fake()->boolean(70),
            'user_id' => $user->id,
            'title' => fake()->title(),
            'content' => fake()->sentence(),
            'meta' => ['abc' => 'abc'],
        ];
    }
}
