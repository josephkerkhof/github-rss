<?php

declare(strict_types=1);

namespace Tests\Feature\Domains\Repositories\Http\Controllers;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RepositoryControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    #[Test]
    public function authenticatedUserWithRepositories_fetchingRepositories_returnsUserRepositories(): void
    {
        // Given
        $user = User::factory()->create();
        $repositories = Repository::factory()->count(3)->for($user)->create();

        // When
        $response = $this->actingAs($user)->getJson('/api/repositories');

        // Then
        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJson([
                [
                    'uuid' => $repositories[0]->uuid,
                    'name' => $repositories[0]->name,
                    'slug' => sprintf('%s/%s', $repositories[0]->owner, $repositories[0]->repo),
                ],
                [
                    'uuid' => $repositories[1]->uuid,
                    'name' => $repositories[1]->name,
                    'slug' => sprintf('%s/%s', $repositories[1]->owner, $repositories[1]->repo),
                ],
                [
                    'uuid' => $repositories[2]->uuid,
                    'name' => $repositories[2]->name,
                    'slug' => sprintf('%s/%s', $repositories[2]->owner, $repositories[2]->repo),
                ],
            ]);
    }

    #[Test]
    public function authenticatedUserWithoutRepositories_fetchingRepositories_returnsEmptyArray(): void
    {
        // Given
        $user = User::factory()->create();

        // When
        $response = $this->actingAs($user)->getJson('/api/repositories');

        // Then
        $response->assertOk()
            ->assertJsonCount(0);
    }

    #[Test]
    public function authenticatedUser_fetchingRepositories_doesNotReturnOtherUsersRepositories(): void
    {
        // Given
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Repository::factory()->count(2)->create(['user_id' => $otherUser->id]);
        $userRepository = Repository::factory()->create(['user_id' => $user->id]);

        // When
        $response = $this->actingAs($user)->getJson('/api/repositories');

        // Then
        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJson([
                [
                    'uuid' => $userRepository->uuid,
                    'name' => $userRepository->name,
                    'slug' => sprintf('%s/%s', $userRepository->owner, $userRepository->repo),
                ],
            ]);
    }

    #[Test]
    public function unauthenticatedUser_fetchingRepositories_returnsUnauthorized(): void
    {
        // When
        $response = $this->getJson('/api/repositories');

        // Then
        $response->assertUnauthorized();
    }
}
