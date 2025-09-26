<?php

declare(strict_types=1);

namespace Tests\Integration\Domains\PullRequets\Retrievers;

use App\Common\Contracts\GitHubRetrieverInterface;
use App\Domains\PullRequests\Enums\IssueFilter;
use App\Domains\PullRequests\Retrievers\IssueRetriever;
use App\Models\Repository;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Collection;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Doubles\GitHubIssueResponseToIssueDataMapperStub;
use Tests\Doubles\GitHubRetrieverSpy;
use Tests\TestCase;

#[CoversClass(IssueRetriever::class)]
final class IssueRetrieverTest extends TestCase
{
    use LazilyRefreshDatabase;

    private GitHubRetrieverInterface $spy;

    private IssueRetriever $retriever;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->spy = new GitHubRetrieverSpy();
        $this->retriever = new IssueRetriever(
            $this->spy,
            new GitHubIssueResponseToIssueDataMapperStub()
        );
    }

    #[Test]
    public function repositoryWithoutFilters_retrieve_submitsGitHubSearchWithoutFilters(): void
    {
        // Given
        $repository = Repository::factory()->create([
            'owner' => 'laravel',
            'repo' => 'framework',
        ]);

        // When
        $this->retriever->retrieve($repository);

        // Then
        self::assertEquals(1, $this->spy->methodCallCount['retrieveIssues']);
        self::assertEquals('repo:laravel/framework', $this->spy->parameters['query']);
    }

    #[Test]
    public function repositoryWithFilters_retrieve_submitsGitHubSearchWithFilters(): void
    {
        // Given
        $repository = Repository::factory()->create([
            'owner' => 'laravel',
            'repo' => 'framework',
        ]);
        $filters = new Collection([
            IssueFilter::IS_MERGED,
            IssueFilter::IS_PR
        ]);

        // When
        $this->retriever->retrieve($repository, $filters);

        // Then
        self::assertEquals(1, $this->spy->methodCallCount['retrieveIssues']);
        self::assertEquals('repo:laravel/framework is:merged is:pr', $this->spy->parameters['query']);
    }
}
