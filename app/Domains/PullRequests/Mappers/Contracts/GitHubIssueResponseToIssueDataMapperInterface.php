<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Mappers\Contracts;

use App\Domains\PullRequests\Schema\IssueData;

interface GitHubIssueResponseToIssueDataMapperInterface
{
    public function map(array $input): IssueData;
}
