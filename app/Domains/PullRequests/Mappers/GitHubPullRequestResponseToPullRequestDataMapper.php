<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Mappers;

use App\Domains\PullRequests\Mappers\Contracts\GitHubPullRequestResponseToPullRequestDataMapperInterface;
use App\Domains\PullRequests\Schema\AuthorData;
use App\Domains\PullRequests\Schema\PullRequestData;
use Carbon\CarbonImmutable;

final readonly class GitHubPullRequestResponseToPullRequestDataMapper implements GitHubPullRequestResponseToPullRequestDataMapperInterface
{
    public function map(array $input): PullRequestData
    {
        return new PullRequestData(
            author: new AuthorData(
                username: $input['user']['login'],
                profileUrl: $input['user']['html_url'],
            ),
            targetBranchName: $input['base']['ref'],
            number: $input['number'],
            title: $input['title'],
            body: $input['body'],
            url: $input['html_url'],
            mergedAt: CarbonImmutable::parse($input['merged_at']),
        );
    }
}
