<?php

declare(strict_types=1);

namespace Tests\Integration\Domains\PullRequets\Retrievers;

use App\Common\Contracts\GitHubRetrieverInterface;
use App\Domains\PullRequests\Retrievers\PullRequestRetriever;
use App\Domains\PullRequests\Schema\IssueData;
use App\Models\Repository;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Doubles\GitHubRetrieverSpy;
use Tests\Doubles\GitHubPullRequestResponseToPullRequestDataMapperStub;
use Tests\TestCase;

#[CoversClass(PullRequestRetriever::class)]
final class PullRequestRetrieverTest extends TestCase
{
    use LazilyRefreshDatabase;

    private GitHubRetrieverInterface $spy;

    private PullRequestRetriever $retriever;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->spy = new GitHubRetrieverSpy();
        $this->retriever = new PullRequestRetriever(
            $this->spy,
            new GitHubPullRequestResponseToPullRequestDataMapperStub(),
        );
    }

    #[Test]
    public function repositoryAndIssue_retrieve_callsRetrievePullRequest(): void
    {
        // Given
        $repository = Repository::factory()->create();
        $issue = new IssueData(
            123,
            'https://example.com'
        );

        // When
        $this->retriever->retrieve($repository, $issue);

        // Then
        self::assertEquals(1, $this->spy->methodCallCount['retrievePullRequest']);
    }
}
