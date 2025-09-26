<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Mappers\Contracts;

use App\Domains\PullRequests\Schema\PullRequestData;

interface GitHubPullRequestResponseToPullRequestDataMapperInterface
{
    public function map(array $input): PullRequestData;
}
