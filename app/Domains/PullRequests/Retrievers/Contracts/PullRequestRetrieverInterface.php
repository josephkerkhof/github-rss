<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Retrievers\Contracts;

use App\Domains\PullRequests\Schema\IssueData;
use App\Domains\PullRequests\Schema\PullRequestData;
use App\Models\Repository;

interface PullRequestRetrieverInterface
{
    public function retrieve(Repository $repository, IssueData $issue): PullRequestData;
}
