<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Domains\PullRequests\Mappers\Contracts\GitHubIssueResponseToIssueDataMapperInterface;
use App\Domains\PullRequests\Schema\IssueData;

final readonly class GitHubIssueResponseToIssueDataMapperStub implements GitHubIssueResponseToIssueDataMapperInterface
{

    public function map(array $input): IssueData
    {
        return new IssueData(
            123,
            'https://example.com',
        );
    }
}
