<?php

namespace Database\Factories\Comment;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::first();
        if (blank($user)) {
            $user = User::factory()->create();
        }
        return [
            'user_id' => $user->id,
            'content' => fake()->paragraph(),
            'commentable_type' => User::class,
            'commentable_id' => $user->id,
            'action' => 'default',
        ];
    }
}
