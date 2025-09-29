<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\PullRequests\Commands\Contracts\CreatePullRequestCommandInterface;
use App\Domains\PullRequests\Enums\IssueFilter;
use App\Domains\PullRequests\Retrievers\Contracts\IssueRetrieverInterface;
use App\Domains\PullRequests\Retrievers\Contracts\PullRequestRetrieverInterface;
use App\Domains\PullRequests\Schema\PullRequestData;
use App\Models\PullRequest;
use App\Models\Repository;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Command\Command as CommandAlias;

final class FetchPullRequests extends Command
{
    protected $signature = 'app:fetch-pull-requests';

    protected $description = 'Fetches the latest pull requests from GitHub and stores them in the database.';

    public function __construct(
        private readonly IssueRetrieverInterface $issueRetriever,
        private readonly PullRequestRetrieverInterface $pullRequestRetriever,
        private readonly CreatePullRequestCommandInterface $createPullRequestCommand,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        Repository::all()->chunk(100)->each(function ($chunk): void {
            foreach ($chunk as $repository) {
                $this->fetchByRepository($repository);
            }
        });

        $this->info('All pull requests fetched successfully.');
        return CommandAlias::SUCCESS;
    }

    private function fetchByRepository(Repository $repository): void
    {
        $filters = new Collection([
            IssueFilter::IS_PR,
            IssueFilter::IS_MERGED
        ]);

        $mergedPullRequests = $this->issueRetriever->retrieve($repository, $filters);

        $alreadyExistingPullRequests = PullRequest::query()
            ->whereBelongsTo($repository)
            ->whereIn('number', $mergedPullRequests->pluck('number'))
            ->get();

        $pullRequestsToFetch = $mergedPullRequests
            ->reject(fn ($pullRequest) => $alreadyExistingPullRequests->contains('number', $pullRequest->number));

        /** @var Collection<int, PullRequestData> $fetchedPullRequests */
        $fetchedPullRequests = new Collection();
        foreach ($pullRequestsToFetch as $pullRequest) {
            $fetchedPullRequests->push($this->pullRequestRetriever->retrieve($repository, $pullRequest));
        }

        if ($fetchedPullRequests->isEmpty()) {
            $this->info("No new pull requests found for repository: {$repository->name}");

            return;
        }

        $this->createPullRequestCommand->handle($repository, $fetchedPullRequests);
    }
}
