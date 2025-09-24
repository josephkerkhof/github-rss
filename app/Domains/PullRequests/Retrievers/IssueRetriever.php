<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Retrievers;

use App\Domains\PullRequests\Enums\IssueFilter;
use App\Domains\PullRequests\Mappers\GitHubIssueResponseToIssueDataMapper;
use App\Domains\PullRequests\Schema\IssueData;
use App\Models\Repository;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Support\Collection;
use Log;

final readonly class IssueRetriever
{
    public function __construct(
        private GitHubIssueResponseToIssueDataMapper $mapper,
    ) {
    }

    /**
     * @param ?Collection<int, IssueFilter> $filters
     * @return Collection<int, IssueData>
     */
    public function retrieve(Repository $repository, ?Collection $filters): Collection
    {
        Log::info("Fetching merged pull requests for repository: {$repository->name}");

        $filters = $filters->map(fn (IssueFilter $filter) => $filter->value)->implode(' ');

        $issuesResponse = GitHub::search()->issues("repo:{$repository->slug} $filters");

        return collect($issuesResponse['items'])->map($this->mapper->map(...));
    }
}
