<?php

namespace App\Domains\PullRequests\Retrievers;

use App\Domains\PullRequests\Mappers\GitHubPullRequestResponseToPullRequestDataMapper;
use App\Domains\PullRequests\Schema\IssueData;
use App\Domains\PullRequests\Schema\PullRequestData;
use App\Models\Repository;
use GrahamCampbell\GitHub\Facades\GitHub;

final readonly class PullRequestRetriever
{
    public function __construct(
        private GitHubPullRequestResponseToPullRequestDataMapper $mapper,
    ) {
    }

    public function retrieve(Repository $repository, IssueData $issue): PullRequestData
    {
        $pullRequestDetails = GitHub::pullRequests()->show($repository->owner, $repository->repo, $issue->number);

        return $this->mapper->map($pullRequestDetails);
    }
}
