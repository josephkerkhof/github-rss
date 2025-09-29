<?php

declare(strict_types=1);

namespace Tests\Integration\Console\Commands;

use Override;
use App\Console\Commands\FetchPullRequests;
use App\Domains\PullRequests\Commands\Contracts\CreatePullRequestCommandInterface;
use App\Domains\PullRequests\Retrievers\Contracts\IssueRetrieverInterface;
use App\Domains\PullRequests\Retrievers\Contracts\PullRequestRetrieverInterface;
use App\Domains\PullRequests\Schema\IssueData;
use App\Models\Branch;
use App\Models\PullRequest;
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

    private PullRequestRetrieverSpy $pullRequestRetrieverSpy;

    private CreatePullRequestCommandSpy $createPullRequestCommandSpy;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->issueRetrieverFake = new IssueRetrieverFake();
        $this->pullRequestRetrieverSpy = new PullRequestRetrieverSpy();
        $this->createPullRequestCommandSpy = new CreatePullRequestCommandSpy();

        $this->app->instance(IssueRetrieverInterface::class, $this->issueRetrieverFake);
        $this->app->instance(PullRequestRetrieverInterface::class, $this->pullRequestRetrieverSpy);
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
        self::assertEquals(0, $this->pullRequestRetrieverSpy->methodCallCount['retrieve']);
        self::assertEquals(0, $this->createPullRequestCommandSpy->methodCallCount['handle']);
    }

    #[Test]
    public function existingRepositoryPullRequests_runCommand_fetchesPullRequestsAndWeSeeExpectedCounts(): void
    {
        // Given
        Repository::factory()->create();
        $this->issueRetrieverFake->retrievedIssues = new Collection([
            new IssueData(
                12345,
                'https://example.com/issue-12345'
            ),
            new IssueData(
                12346,
                'https://example.com/issue-12346'
            ),
        ]);


        // When
        $this->artisan('app:fetch-pull-requests');

        // Then the retriever was called once
        self::assertEquals(1, $this->issueRetrieverFake->methodCallCount['retrieve']);

        // Since both merged pull requests are not in the database, we should receive two calls to the retriever for
        // each merged PR.
        self::assertEquals(2, $this->pullRequestRetrieverSpy->methodCallCount['retrieve']);
        // and we should be submitting one invocation to the command to create the pull requests to our database
        self::assertEquals(1, $this->createPullRequestCommandSpy->methodCallCount['handle']);
    }

    #[Test]
    public function existingRepositoryPullRequestsAlreadyExistInDatabase_runCommand_fetchesPullRequestsAndSeeExpectedCounts(): void
    {
        // Given
        $repository = Repository::factory()->create();
        $branch = Branch::factory()->for($repository)->create();
        PullRequest::factory()
            ->for($repository)
            ->for($branch)
            ->forEachSequence(
                ['number' => 12345],
                ['number' => 12346],
            )
            ->createMany();
        $this->issueRetrieverFake->retrievedIssues = new Collection([
            new IssueData(
                12345,
                'https://example.com/issue-12345'
            ),
            new IssueData(
                12346,
                'https://example.com/issue-12346'
            ),
        ]);

        // When
        $this->artisan('app:fetch-pull-requests');

        // Then the retriever was called once
        self::assertEquals(1, $this->issueRetrieverFake->methodCallCount['retrieve']);

        // Since the pull requests already exist in the database, we should not receive any calls to the retriever or command
        self::assertEquals(0, $this->pullRequestRetrieverSpy->methodCallCount['retrieve']);
        self::assertEquals(0, $this->createPullRequestCommandSpy->methodCallCount['handle']);
    }
}
