<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Retrievers\Contracts;

use App\Domains\PullRequests\Enums\IssueFilter;
use App\Domains\PullRequests\Schema\IssueData;
use App\Models\Repository;
use Illuminate\Support\Collection;

interface IssueRetrieverInterface
{
    /**
     * @param ?Collection<int, covariant IssueFilter> $filters
     * @return Collection<int, IssueData>
     */
    public function retrieve(Repository $repository, ?Collection $filters = null): Collection;
}
