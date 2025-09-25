<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Retrievers;

use App\Common\Contracts\GitHubRetrieverInterface;
use App\Domains\PullRequests\Enums\IssueFilter;
use App\Domains\PullRequests\Mappers\GitHubIssueResponseToIssueDataMapper;
use App\Domains\PullRequests\Schema\IssueData;
use App\Models\Repository;
use Illuminate\Support\Collection;
use Log;

final readonly class IssueRetriever
{
    public function __construct(
        private GitHubRetrieverInterface $retriever,
        private GitHubIssueResponseToIssueDataMapper $mapper,
    ) {
    }

    /**
     * @param ?Collection<int, covariant IssueFilter> $filters
     * @return Collection<int, IssueData>
     */
    public function retrieve(Repository $repository, ?Collection $filters = null): Collection
    {
        Log::info("Fetching merged pull requests for repository: {$repository->name}");

        $query = new Collection(["repo:{$repository->slug}"])
            ->merge(
                $filters?->map(fn (IssueFilter $filter) => $filter->value)
            )
            ->implode(' ');

        $issuesResponse = $this->retriever->retrieveIssues($query);

        return collect($issuesResponse['items'])->map($this->mapper->map(...));
    }
}
