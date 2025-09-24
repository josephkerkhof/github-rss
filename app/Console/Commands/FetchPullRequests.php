<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\PullRequests\Commands\CreatePullRequestCommand;
use App\Domains\PullRequests\Enums\IssueFilter;
use App\Domains\PullRequests\Retrievers\IssueRetriever;
use App\Domains\PullRequests\Retrievers\PullRequestRetriever;
use App\Domains\PullRequests\Schema\PullRequestData;
use App\Models\PullRequest;
use App\Models\Repository;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Command\Command as CommandAlias;

class FetchPullRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-pull-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the latest pull requests from GitHub and stores them in the database.';

    public function __construct(
        private readonly IssueRetriever $issueRetriever,
        private readonly PullRequestRetriever $pullRequestRetriever,
        private readonly CreatePullRequestCommand $createPullRequestCommand,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
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

        $this->createPullRequestCommand->handle($repository, $fetchedPullRequests);
    }
}
