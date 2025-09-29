<?php

namespace Tests\Integration\Console\Commands;

use App\Console\Commands\FetchPullRequests;
use App\Domains\PullRequests\Commands\Contracts\CreatePullRequestCommandInterface;
use App\Domains\PullRequests\Retrievers\Contracts\IssueRetrieverInterface;
use App\Domains\PullRequests\Retrievers\Contracts\PullRequestRetrieverInterface;
use App\Models\Repository;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Doubles\CreatePullRequestCommandSpy;
use Tests\Doubles\IssueRetrieverSpy;
use Tests\Doubles\PullRequestRetrieverSpy;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(FetchPullRequests::class)]
final class FetchPullRequestsTest extends TestCase
{
    use LazilyRefreshDatabase;

    private IssueRetrieverSpy $issueRetrieverSpy;

    private PullRequestRetrieverSpy $pullRequestRetrieverSpy;

    private CreatePullRequestCommandSpy $createPullRequestCommandSpy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->issueRetrieverSpy = new IssueRetrieverSpy();
        $this->pullRequestRetrieverSpy = new PullRequestRetrieverSpy();
        $this->createPullRequestCommandSpy = new CreatePullRequestCommandSpy();

        $this->app->instance(IssueRetrieverInterface::class, $this->issueRetrieverSpy);
        $this->app->instance(PullRequestRetrieverInterface::class, $this->pullRequestRetrieverSpy);
        $this->app->instance(CreatePullRequestCommandInterface::class, $this->createPullRequestCommandSpy);
    }

    #[Test]
    public function existingRepositoryWithNoPullRequestsFound_runCommand_fetchesPullRequestsAndWeSeeExpectedCounts(): void
    {
        // Given
        Repository::factory()->create();

        // When
        $this->artisan('app:fetch-pull-requests');

        // Then the retriever was called once
        self::assertEquals(1, $this->issueRetrieverSpy->methodCallCount['retrieve']);

        // and since no pull requests were found, the subsequent retriever and command was not called
        self::assertEquals(0, $this->pullRequestRetrieverSpy->methodCallCount['retrieve']);
        self::assertEquals(0, $this->createPullRequestCommandSpy->methodCallCount['handle']);
    }
}
