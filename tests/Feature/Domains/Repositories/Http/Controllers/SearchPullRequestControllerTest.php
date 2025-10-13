<?php

declare(strict_types=1);

namespace Tests\Feature\Domains\Repositories\Http\Controllers;

use App\Models\Author;
use App\Models\Branch;
use App\Models\PullRequest;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SearchPullRequestControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    #[Test]
    public function authenticatedUserWithRepository_searchingPullRequestsByNumbers_returnsPullRequests(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();
        $branch = Branch::factory()->for($repository)->create();
        $author = Author::factory()->create();

        $pr1 = PullRequest::factory()->for($repository)->for($branch)->for($author)->create(['number' => 100]);
        $pr2 = PullRequest::factory()->for($repository)->for($branch)->for($author)->create(['number' => 200]);
        PullRequest::factory()->for($repository)->for($branch)->for($author)->create(['number' => 300]);

        // When
        $response = $this->actingAs($user)->postJson("/api/{$repository->uuid}/list", [
            'numbers' => [100, 200],
            'per_page' => 10,
        ]);

        // Then
        $response->assertOk()
            ->assertJsonCount(2, 'data');

        self::assertEqualsCanonicalizing(
            [
                [
                    'title' => $pr1->title,
                    'body' => $pr1->body,
                    'number' => $pr1->number,
                    'branch' => $branch->name,
                    'author_username' => $author->username,
                    'author_profile_url' => $author->profile_url,
                    'url' => $pr1->url,
                    'merged_at' => $pr1->merged_at->toIso8601String(),
                ],
                [
                    'title' => $pr2->title,
                    'body' => $pr2->body,
                    'number' => $pr2->number,
                    'branch' => $branch->name,
                    'author_username' => $author->username,
                    'author_profile_url' => $author->profile_url,
                    'url' => $pr2->url,
                    'merged_at' => $pr2->merged_at->toIso8601String(),
                ],
            ],
            $response->json('data')
        );
    }

    #[Test]
    public function authenticatedUserWithRepository_searchingPullRequestsByGithubUsernames_returnsPullRequests(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();
        $branch = Branch::factory()->for($repository)->create();

        $author1 = Author::factory()->create(['username' => 'john-doe']);
        $author2 = Author::factory()->create(['username' => 'jane-smith']);
        $author3 = Author::factory()->create(['username' => 'other-user']);

        $pr1 = PullRequest::factory()->for($repository)->for($branch)->for($author1)->create();
        $pr2 = PullRequest::factory()->for($repository)->for($branch)->for($author2)->create();
        PullRequest::factory()->for($repository)->for($branch)->for($author3)->create();

        // When
        $response = $this->actingAs($user)->postJson("/api/{$repository->uuid}/list", [
            'github_usernames' => ['john-doe', 'jane-smith'],
            'per_page' => 10,
        ]);

        // Then
        $response->assertOk()
            ->assertJsonCount(2, 'data');

        self::assertEqualsCanonicalizing(
            [$pr1->number, $pr2->number],
            array_column($response->json('data'), 'number')
        );
    }

    #[Test]
    public function authenticatedUserWithRepository_searchingPullRequestsByBranchUuids_returnsPullRequests(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();

        $branch1 = Branch::factory()->for($repository)->create(['name' => 'feature/auth']);
        $branch2 = Branch::factory()->for($repository)->create(['name' => 'feature/api']);
        $branch3 = Branch::factory()->for($repository)->create(['name' => 'feature/other']);

        $author = Author::factory()->create();

        $pr1 = PullRequest::factory()->for($repository)->for($branch1)->for($author)->create();
        $pr2 = PullRequest::factory()->for($repository)->for($branch2)->for($author)->create();
        PullRequest::factory()->for($repository)->for($branch3)->for($author)->create();

        // When
        $response = $this->actingAs($user)->postJson("/api/{$repository->uuid}/list", [
            'branch_uuids' => [$branch1->uuid, $branch2->uuid],
            'per_page' => 10,
        ]);

        // Then
        $response->assertOk()
            ->assertJsonCount(2, 'data');

        self::assertEqualsCanonicalizing(
            [$pr1->number, $pr2->number],
            array_column($response->json('data'), 'number')
        );
    }

    #[Test]
    public function authenticatedUserWithRepository_searchingPullRequestsWithPagination_returnsPaginatedResults(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();
        $branch = Branch::factory()->for($repository)->create();
        $author = Author::factory()->create();

        // Create 5 pull requests
        PullRequest::factory()->count(5)->for($repository)->for($branch)->for($author)->create();

        // When - Get first page with 2 items
        $response = $this->actingAs($user)->postJson("/api/{$repository->uuid}/list", [
            'per_page' => 2,
        ]);

        // Then
        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data',
                'path',
                'per_page',
                'next_cursor',
                'next_page_url',
            ]);

        self::assertNotNull($response->json('next_cursor'));
        self::assertNotNull($response->json('next_page_url'));

        // When - Get second page
        $nextCursor = $response->json('next_cursor');
        $secondPageResponse = $this->actingAs($user)->postJson("/api/{$repository->uuid}/list", [
            'per_page' => 2,
            'cursor' => $nextCursor,
        ]);

        // Then
        $secondPageResponse->assertOk()
            ->assertJsonCount(2, 'data');

        // Ensure no duplicate data between pages
        $firstPageIds = array_column($response->json('data'), 'id');
        $secondPageIds = array_column($secondPageResponse->json('data'), 'id');
        self::assertEmpty(array_intersect($firstPageIds, $secondPageIds));
    }

    #[Test]
    public function authenticatedUserWithRepository_searchingPullRequestsWithNoCursor_returnsFirstPage(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();
        $branch = Branch::factory()->for($repository)->create();
        $author = Author::factory()->create();

        PullRequest::factory()->count(3)->for($repository)->for($branch)->for($author)->create();

        // When
        $response = $this->actingAs($user)->postJson("/api/{$repository->uuid}/list", [
            'per_page' => 10,
        ]);

        // Then
        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function authenticatedUserWithRepository_searchingPullRequestsWithNoResults_returnsEmptyArray(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();

        // When
        $response = $this->actingAs($user)->postJson("/api/{$repository->uuid}/list", [
            'numbers' => [999],
            'per_page' => 10,
        ]);

        // Then
        $response->assertOk()
            ->assertJsonCount(0, 'data');

        self::assertNull($response->json('next_cursor'));
    }

    #[Test]
    public function authenticatedUser_searchingPullRequestsFromAnotherUsersRepository_returnsForbidden(): void
    {
        // Given
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherRepository = Repository::factory()->for($otherUser)->create();

        // When
        $response = $this->actingAs($user)->postJson("/api/{$otherRepository->uuid}/list", [
            'per_page' => 10,
        ]);

        // Then
        $response->assertForbidden();
    }

    #[Test]
    public function unauthenticatedUser_searchingPullRequests_returnsUnauthorized(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();

        // When
        $response = $this->postJson("/api/{$repository->uuid}/list", [
            'per_page' => 10,
        ]);

        // Then
        $response->assertUnauthorized();
    }

    #[Test]
    public function authenticatedUser_searchingPullRequestsWithInvalidUuid_returnsNotFound(): void
    {
        // Given
        $user = User::factory()->create();

        // When
        $response = $this->actingAs($user)->postJson('/api/invalid-uuid/list', [
            'per_page' => 10,
        ]);

        // Then
        $response->assertNotFound();
    }

    #[Test]
    public function authenticatedUserWithRepository_searchingPullRequestsWithMultipleFilters_returnsFilteredPullRequests(): void
    {
        // Given
        $user = User::factory()->create();
        $repository = Repository::factory()->for($user)->create();

        $branch1 = Branch::factory()->for($repository)->create(['name' => 'feature/auth']);
        $branch2 = Branch::factory()->for($repository)->create(['name' => 'feature/api']);

        $author1 = Author::factory()->create(['username' => 'john-doe']);
        $author2 = Author::factory()->create(['username' => 'jane-smith']);

        // This should match: branch1 AND author1 AND number 100
        $matchingPr = PullRequest::factory()->for($repository)->for($branch1)->for($author1)->create(['number' => 100]);

        // These should not match
        PullRequest::factory()->for($repository)->for($branch2)->for($author1)->create(['number' => 101]); // Wrong branch
        PullRequest::factory()->for($repository)->for($branch1)->for($author2)->create(['number' => 102]); // Wrong author
        PullRequest::factory()->for($repository)->for($branch1)->for($author1)->create(['number' => 200]); // Wrong number

        // When
        $response = $this->actingAs($user)->postJson("/api/{$repository->uuid}/list", [
            'branch_uuids' => [$branch1->uuid],
            'github_usernames' => ['john-doe'],
            'numbers' => [100],
            'per_page' => 10,
        ]);

        // Then
        $response->assertOk()
            ->assertJsonCount(1, 'data');

        self::assertEquals($matchingPr->number, $response->json('data.0.number'));
    }
}
