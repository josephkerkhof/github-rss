<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Commands\Contracts;

use App\Domains\PullRequests\Schema\PullRequestData;
use App\Models\Repository;
use Illuminate\Support\Collection;

interface CreatePullRequestCommandInterface
{
    /**
     * @param Collection<int, PullRequestData> $pullRequests
     */
    public function handle(Repository $repository, Collection $pullRequests): void;
}
