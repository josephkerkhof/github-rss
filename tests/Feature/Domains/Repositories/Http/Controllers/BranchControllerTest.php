<?php

declare(strict_types=1);

namespace Tests\Feature\Domains\Repositories\Http\Controllers;

use App\Models\Branch;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BranchControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    #[Test]
    public function authenticatedUserWithRepository_fetchingBranches_returnsRepositoryBranches(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();
        $branches = Branch::factory()->count(3)->for($repository)->create();

        // When
        $response = $this->actingAs($user)->getJson("/api/{$repository->uuid}/branches");

        // Then
        $response->assertOk()
            ->assertJsonCount(3);

        self::assertEqualsCanonicalizing(
            [
                [
                    'uuid' => $branches[0]->uuid,
                    'name' => $branches[0]->name,
                ],
                [
                    'uuid' => $branches[1]->uuid,
                    'name' => $branches[1]->name,
                ],
                [
                    'uuid' => $branches[2]->uuid,
                    'name' => $branches[2]->name,
                ],
            ],
            $response->json()
        );
    }

    #[Test]
    public function authenticatedUserWithRepository_fetchingBranchesFromRepositoryWithoutBranches_returnsEmptyArray(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();

        // When
        $response = $this->actingAs($user)->getJson("/api/{$repository->uuid}/branches");

        // Then
        $response->assertOk()
            ->assertJsonCount(0);
    }

    #[Test]
    public function authenticatedUser_fetchingBranchesFromAnotherUsersRepository_returnsNotFound(): void
    {
        // Given
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherRepository = Repository::factory()->for($otherUser)->create();
        Branch::factory()->count(2)->for($otherRepository)->create();

        // When
        $response = $this->actingAs($user)->getJson("/api/{$otherRepository->uuid}/branches");

        // Then
        $response->assertForbidden();
    }

    #[Test]
    public function unauthenticatedUser_fetchingBranches_returnsUnauthorized(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();

        // When
        $response = $this->getJson("/api/{$repository->uuid}/branches");

        // Then
        $response->assertUnauthorized();
    }

    #[Test]
    public function authenticatedUser_fetchingBranchesWithInvalidUuid_returnsNotFound(): void
    {
        // Given
        $user = User::factory()->create();

        // When
        $response = $this->actingAs($user)->getJson('/api/invalid-uuid/branches');

        // Then
        $response->assertNotFound();
    }
}
