<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Mappers;

use App\Domains\PullRequests\Schema\IssueData;

final readonly class GitHubIssueResponseToIssueDataMapper
{
    public function map(array $response): IssueData
    {
        return new IssueData(
            $response['number'],
            $response['pull_request']['html_url'],
        );
    }
}
