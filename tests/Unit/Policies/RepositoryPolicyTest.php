<?php

declare(strict_types=1);

namespace Tests\Unit\Policies;

use App\Models\Repository;
use App\Models\User;
use App\Policies\RepositoryPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RepositoryPolicyTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        Factory::dontExpandRelationshipsByDefault();
    }

    #[Test]
    public function userOwnsRepository_viewingRepository_returnsTrue(): void
    {
        // Given
        $user = User::factory()->make(['id' => 1]);
        $repository = Repository::factory()->make(['user_id' => 1]);
        $policy = new RepositoryPolicy();

        // When
        $result = $policy->view($user, $repository);

        // Then
        $this->assertTrue($result);
    }

    #[Test]
    public function userDoesNotOwnRepository_viewingRepository_returnsFalse(): void
    {
        // Given
        $user = User::factory()->make(['id' => 1]);
        $repository = Repository::factory()->make(['user_id' => 2]);
        $policy = new RepositoryPolicy();

        // When
        $result = $policy->view($user, $repository);

        // Then
        $this->assertFalse($result);
    }

    #[Test]
    public function authenticatedUser_creatingRepository_returnsTrue(): void
    {
        // Given
        $user = User::factory()->make(['id' => 1]);
        $policy = new RepositoryPolicy();

        // When
        $result = $policy->create($user);

        // Then
        $this->assertTrue($result);
    }

    #[Test]
    public function userOwnsRepository_updatingRepository_returnsTrue(): void
    {
        // Given
        $user = User::factory()->make(['id' => 1]);
        $repository = Repository::factory()->make(['user_id' => 1]);
        $policy = new RepositoryPolicy();

        // When
        $result = $policy->update($user, $repository);

        // Then
        $this->assertTrue($result);
    }

    #[Test]
    public function userDoesNotOwnRepository_updatingRepository_returnsFalse(): void
    {
        // Given
        $user = User::factory()->make(['id' => 1]);
        $repository = Repository::factory()->make(['user_id' => 2]);
        $policy = new RepositoryPolicy();

        // When
        $result = $policy->update($user, $repository);

        // Then
        $this->assertFalse($result);
    }

    #[Test]
    public function userOwnsRepository_deletingRepository_returnsTrue(): void
    {
        // Given
        $user = User::factory()->make(['id' => 1]);
        $repository = Repository::factory()->make(['user_id' => 1]);
        $policy = new RepositoryPolicy();

        // When
        $result = $policy->delete($user, $repository);

        // Then
        $this->assertTrue($result);
    }

    #[Test]
    public function userDoesNotOwnRepository_deletingRepository_returnsFalse(): void
    {
        // Given
        $user = User::factory()->make(['id' => 1]);
        $repository = Repository::factory()->make(['user_id' => 2]);
        $policy = new RepositoryPolicy();

        // When
        $result = $policy->delete($user, $repository);

        // Then
        $this->assertFalse($result);
    }
}
