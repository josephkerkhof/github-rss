<?php

namespace Tests\Doubles;

use App\Domains\PullRequests\Retrievers\Contracts\IssueRetrieverInterface;
use App\Models\Repository;
use Illuminate\Support\Collection;
use Tests\Doubles\Concerns\InitializesCallCounts;

final class IssueRetrieverFake implements IssueRetrieverInterface
{
    use InitializesCallCounts;

    public array $parameters;

    public array $methodCallCount;

    public Collection $retrievedIssues;

    public function __construct()
    {
        $this->methodCallCount = $this->initializeMethodCallCount();
    }

    /**
     * @inheritDoc
     */
    public function retrieve(Repository $repository, ?Collection $filters = null): Collection
    {
        $this->parameters = [
            'repository' => $repository,
            'filters' => $filters,
        ];

        $this->methodCallCount['retrieve']++;

        return $this->retrievedIssues;
    }
}
