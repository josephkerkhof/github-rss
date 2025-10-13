<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Branch;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PullRequest>
 */
class PullRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'repository_id' => Repository::factory(),
            'branch_id' => Branch::factory(),
            'author_id' => Author::factory(),
            'number' => fake()->unique()->numberBetween(25_000, 100_000),
            'title' => fake()->title(),
            'body' => fake()->text(),
            'url' => fake()->url(),
            'merged_at' => fake()->dateTime(),
        ];
    }
}
