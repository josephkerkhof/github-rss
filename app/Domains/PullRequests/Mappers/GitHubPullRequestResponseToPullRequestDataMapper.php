<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Mappers;

use App\Domains\PullRequests\Schema\AuthorData;
use App\Domains\PullRequests\Schema\PullRequestData;

final readonly class GitHubPullRequestResponseToPullRequestDataMapper
{
    public function map(array $pullRequestResponse): PullRequestData
    {
        return new PullRequestData(
            author: new AuthorData(
                username: $pullRequestResponse['user']['login'],
                profileUrl: $pullRequestResponse['user']['html_url'],
            ),
            targetBranchName: $pullRequestResponse['base']['ref'],
            number: $pullRequestResponse['number'],
            title: $pullRequestResponse['title'],
            body: $pullRequestResponse['body'],
            url: $pullRequestResponse['html_url'],
            mergedAt: $pullRequestResponse['merged_at'],
        );
    }
}
