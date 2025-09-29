<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Retrievers;

use App\Common\Contracts\GitHubRetrieverInterface;
use App\Domains\PullRequests\Mappers\Contracts\GitHubPullRequestResponseToPullRequestDataMapperInterface;
use App\Domains\PullRequests\Retrievers\Contracts\PullRequestRetrieverInterface;
use App\Domains\PullRequests\Schema\IssueData;
use App\Domains\PullRequests\Schema\PullRequestData;
use App\Models\Repository;

final readonly class PullRequestRetriever implements PullRequestRetrieverInterface
{
    public function __construct(
        private GitHubRetrieverInterface $retriever,
        private GitHubPullRequestResponseToPullRequestDataMapperInterface $mapper,
    ) {
    }

    public function retrieve(Repository $repository, IssueData $issue): PullRequestData
    {
        $pullRequestDetails = $this->retriever->retrievePullRequest($repository->owner, $repository->repo, $issue->number);

        return $this->mapper->map($pullRequestDetails);
    }
}
