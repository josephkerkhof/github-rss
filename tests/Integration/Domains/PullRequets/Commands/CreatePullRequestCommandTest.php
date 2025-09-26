<?php

declare(strict_types=1);

namespace Tests\Integration\Domains\PullRequets\Commands;

use App\Domains\PullRequests\Commands\CreatePullRequestCommand;
use App\Domains\PullRequests\Schema\AuthorData;
use App\Domains\PullRequests\Schema\PullRequestData;
use App\Models\Author;
use App\Models\Branch;
use App\Models\Repository;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Collection;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(CreatePullRequestCommand::class)]
class CreatePullRequestCommandTest extends TestCase
{
    use LazilyRefreshDatabase;

    private CreatePullRequestCommand $command;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->command = $this->app->make(CreatePullRequestCommand::class);
    }

    #[Test]
    public function repositoryWithOnePullRequestAndNewBranchAndAuthor_handle_createsAllNewEntities(): void
    {
        // Given
        $repository = Repository::factory()->create();
        $pullRequests = new Collection([
            new PullRequestData(
                new AuthorData(
                    'muh_username',
                    'https://example.com/profile-url',
                ),
                '12.x',
                54321,
                'Muh Pull Request',
                'Muh Description',
                'https://example.com/pull-request-url',
                CarbonImmutable::parse('2024-12-31 12:31:30')
            )
        ]);

        // When
        $this->command->handle($repository, $pullRequests);

        // Then
        $this->assertDatabaseCount('branches', 1);
        $this->assertDatabaseHas('branches', [
            'repository_id' => $repository->id,
            'name' => '12.x',
        ]);

        $this->assertDatabaseCount('authors', 1);
        $this->assertDatabaseHas('authors', [
            'username' => 'muh_username',
            'profile_url' => 'https://example.com/profile-url',
        ]);

        $this->assertDatabaseCount('pull_requests', 1);
        $author = Author::query()->where('username', 'muh_username')->firstOrFail();
        $branch = Branch::query()->where('name', '12.x')->firstOrFail();
        $this->assertDatabaseHas('pull_requests', [
            'repository_id' => $repository->id,
            'branch_id' => $branch->id,
            'author_id' => $author->id,
            'number' => 54321,
            'title' => 'Muh Pull Request',
            'body' => 'Muh Description',
            'url' => 'https://example.com/pull-request-url',
            'merged_at' => '2024-12-31 12:31:30',
        ]);
    }

    #[Test]
    public function repositoryWithSeveralPullRequestAndNewBranchAndAuthor_handle_createsAllNewEntities(): void
    {
        // Given
        $repository = Repository::factory()->create();
        $author1 = new AuthorData(
            'author_1',
            'https://example.com/author-1-profile-url',
        );
        $author2 = new AuthorData(
            'author_2',
            'https://example.com/author-2-profile-url',
        );

        $pullRequests = new Collection([
            new PullRequestData(
                $author1,
                '12.x',
                54321,
                'Muh Pull Request',
                'Muh Description',
                'https://example.com/pull-request-url-1',
                CarbonImmutable::parse('2024-12-31 12:31:30')
            ),
            new PullRequestData(
                $author2,
                '12.x',
                54322,
                'Muh Other Pull Request',
                'Muh Other Description',
                'https://example.com/pull-request-url-2',
                CarbonImmutable::parse('2025-01-01 08:00:00')
            ),
            new PullRequestData(
                $author1,
                '11.x',
                54323,
                'Muh Silly Pull Request',
                'Muh Silly Description',
                'https://example.com/pull-request-url-3',
                CarbonImmutable::parse('2025-01-02 08:00:00')
            )
        ]);

        // When
        $this->command->handle($repository, $pullRequests);

        // Then
        $this->assertDatabaseCount('branches', 2);
        $this->assertDatabaseHas('branches', [
            'repository_id' => $repository->id,
            'name' => '12.x',
        ]);
        $this->assertDatabaseHas('branches', [
            'repository_id' => $repository->id,
            'name' => '11.x',
        ]);

        $this->assertDatabaseCount('authors', 2);
        $this->assertDatabaseHas('authors', [
            'username' => 'author_1',
            'profile_url' => 'https://example.com/author-1-profile-url',
        ]);
        $this->assertDatabaseHas('authors', [
            'username' => 'author_2',
            'profile_url' => 'https://example.com/author-2-profile-url',
        ]);

        $this->assertDatabaseCount('pull_requests', 3);
        $author1 = Author::query()->where('username', 'author_1')->firstOrFail();
        $author2 = Author::query()->where('username', 'author_2')->firstOrFail();
        $branch12 = Branch::query()->where('name', '12.x')->firstOrFail();
        $branch11 = Branch::query()->where('name', '11.x')->firstOrFail();
        $this->assertDatabaseHas('pull_requests', [
            'repository_id' => $repository->id,
            'branch_id' => $branch12->id,
            'author_id' => $author1->id,
            'number' => 54321,
            'title' => 'Muh Pull Request',
            'body' => 'Muh Description',
            'url' => 'https://example.com/pull-request-url-1',
            'merged_at' => '2024-12-31 12:31:30',
        ]);
        $this->assertDatabaseHas('pull_requests', [
            'repository_id' => $repository->id,
            'branch_id' => $branch12->id,
            'author_id' => $author2->id,
            'number' => 54322,
            'title' => 'Muh Other Pull Request',
            'body' => 'Muh Other Description',
            'url' => 'https://example.com/pull-request-url-2',
            'merged_at' => '2025-01-01 08:00:00',
        ]);
        $this->assertDatabaseHas('pull_requests', [
            'repository_id' => $repository->id,
            'branch_id' => $branch11->id,
            'author_id' => $author1->id,
            'number' => 54323,
            'title' => 'Muh Silly Pull Request',
            'body' => 'Muh Silly Description',
            'url' => 'https://example.com/pull-request-url-3',
            'merged_at' => '2025-01-02 08:00:00',
        ]);
    }
}
