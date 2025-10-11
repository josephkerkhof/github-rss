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

    #[Test]
    public function authenticatedUser_creatingRepository_createsRepository(): void
    {
        // Given
        $user = User::factory()->create();
        $data = [
            'name' => 'Test Repository',
            'owner' => 'testowner',
            'repo' => 'testrepo',
        ];

        // When
        $response = $this->actingAs($user)->postJson('/repositories', $data);

        // Then
        $response->assertCreated()
            ->assertJsonStructure([
                'uuid',
                'name',
                'slug',
            ])
            ->assertJson([
                'name' => 'Test Repository',
                'slug' => 'testowner/testrepo',
            ]);

        $this->assertDatabaseHas('repositories', [
            'name' => 'Test Repository',
            'owner' => 'testowner',
            'repo' => 'testrepo',
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function authenticatedUser_creatingRepositoryWithInvalidData_returnsValidationError(): void
    {
        // Given
        $user = User::factory()->create();
        $data = [
            'name' => '',
            'owner' => '',
            'repo' => '',
        ];

        // When
        $response = $this->actingAs($user)->postJson('/repositories', $data);

        // Then
        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'owner', 'repo']);
    }

    #[Test]
    public function unauthenticatedUser_creatingRepository_returnsUnauthorized(): void
    {
        // Given
        $data = [
            'name' => 'Test Repository',
            'owner' => 'testowner',
            'repo' => 'testrepo',
        ];

        // When
        $response = $this->postJson('/repositories', $data);

        // Then
        $response->assertUnauthorized();
    }

    #[Test]
    public function repositoryOwner_updatingRepository_updatesRepository(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create([
            'name' => 'Original Name',
            'owner' => 'originalowner',
            'repo' => 'originalrepo',
        ]);

        $data = [
            'name' => 'Updated Name',
            'owner' => 'updatedowner',
            'repo' => 'updatedrepo',
        ];

        // When
        $response = $this->actingAs($user)->putJson('/repositories/' . $repository->uuid, $data);

        // Then
        $response->assertOk()
            ->assertJson([
                'uuid' => $repository->uuid,
                'name' => 'Updated Name',
                'slug' => 'updatedowner/updatedrepo',
            ]);

        $this->assertDatabaseHas('repositories', [
            'id' => $repository->id,
            'name' => 'Updated Name',
            'owner' => 'updatedowner',
            'repo' => 'updatedrepo',
        ]);
    }

    #[Test]
    public function otherUser_updatingRepository_returnsForbidden(): void
    {
        // Given
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $repository = Repository::factory()->for($owner)->create();

        $data = [
            'name' => 'Updated Name',
            'owner' => 'updatedowner',
            'repo' => 'updatedrepo',
        ];

        // When
        $response = $this->actingAs($otherUser)->putJson('/repositories/' . $repository->uuid, $data);

        // Then
        $response->assertForbidden();
    }

    #[Test]
    public function unauthenticatedUser_updatingRepository_returnsUnauthorized(): void
    {
        // Given
        $repository = Repository::factory()->create();
        $data = [
            'name' => 'Updated Name',
            'owner' => 'updatedowner',
            'repo' => 'updatedrepo',
        ];

        // When
        $response = $this->putJson('/repositories/' . $repository->uuid, $data);

        // Then
        $response->assertUnauthorized();
    }

    #[Test]
    public function repositoryOwner_deletingRepository_deletesRepository(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();

        // When
        $response = $this->actingAs($user)->deleteJson('/repositories/' . $repository->uuid);

        // Then
        $response->assertNoContent();
        $this->assertDatabaseMissing('repositories', [
            'id' => $repository->id,
        ]);
    }

    #[Test]
    public function otherUser_deletingRepository_returnsForbidden(): void
    {
        // Given
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $repository = Repository::factory()->for($owner)->create();

        // When
        $response = $this->actingAs($otherUser)->deleteJson('/repositories/' . $repository->uuid);

        // Then
        $response->assertForbidden();
        $this->assertDatabaseHas('repositories', [
            'id' => $repository->id,
        ]);
    }

    #[Test]
    public function unauthenticatedUser_deletingRepository_returnsUnauthorized(): void
    {
        // Given
        $repository = Repository::factory()->create();

        // When
        $response = $this->deleteJson('/repositories/' . $repository->uuid);

        // Then
        $response->assertUnauthorized();
    }
}
