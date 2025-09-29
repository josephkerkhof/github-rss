<?php

namespace Tests\Integration\Console\Commands;

use App\Console\Commands\FetchPullRequests;
use App\Domains\PullRequests\Commands\Contracts\CreatePullRequestCommandInterface;
use App\Domains\PullRequests\Retrievers\Contracts\IssueRetrieverInterface;
use App\Domains\PullRequests\Retrievers\Contracts\PullRequestRetrieverInterface;
use App\Models\Repository;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Collection;
use Tests\Doubles\CreatePullRequestCommandSpy;
use Tests\Doubles\IssueRetrieverFake;
use Tests\Doubles\PullRequestRetrieverSpy;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(FetchPullRequests::class)]
final class FetchPullRequestsTest extends TestCase
{
    use LazilyRefreshDatabase;

    private IssueRetrieverFake $issueRetrieverFake;

    private PullRequestRetrieverSpy $pullRequestRetrieverFake;

    private CreatePullRequestCommandSpy $createPullRequestCommandSpy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->issueRetrieverFake = new IssueRetrieverFake();
        $this->pullRequestRetrieverFake = new PullRequestRetrieverSpy();
        $this->createPullRequestCommandSpy = new CreatePullRequestCommandSpy();

        $this->app->instance(IssueRetrieverInterface::class, $this->issueRetrieverFake);
        $this->app->instance(PullRequestRetrieverInterface::class, $this->pullRequestRetrieverFake);
        $this->app->instance(CreatePullRequestCommandInterface::class, $this->createPullRequestCommandSpy);
    }

    #[Test]
    public function existingRepositoryWithNoPullRequestsFound_runCommand_fetchesPullRequestsAndWeSeeExpectedCounts(): void
    {
        // Given
        Repository::factory()->create();
        $this->issueRetrieverFake->retrievedIssues = new Collection();

        // When
        $this->artisan('app:fetch-pull-requests');

        // Then the retriever was called once
        self::assertEquals(1, $this->issueRetrieverFake->methodCallCount['retrieve']);

        // and since no pull requests were found, the subsequent retriever and command was not called
        self::assertEquals(0, $this->pullRequestRetrieverFake->methodCallCount['retrieve']);
        self::assertEquals(0, $this->createPullRequestCommandSpy->methodCallCount['handle']);
    }
}
