<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Mappers\Concerns;

use App\Domains\Repositories\Schema\Responses\PullRequestData;
use App\Models\PullRequest;

interface PullRequestToResponseMapperInterface
{
    public function map(PullRequest $pullRequest): PullRequestData;
}
