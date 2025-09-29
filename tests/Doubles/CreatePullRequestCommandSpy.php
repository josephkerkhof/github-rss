<?php

namespace Tests\Doubles;

use App\Domains\PullRequests\Commands\Contracts\CreatePullRequestCommandInterface;
use App\Models\Repository;
use Illuminate\Support\Collection;
use Tests\Doubles\Concerns\InitializesCallCounts;

final class CreatePullRequestCommandSpy implements CreatePullRequestCommandInterface
{
    use InitializesCallCounts;

    public array $parameters;

    public array $methodCallCount;

    public function __construct()
    {
        $this->methodCallCount = $this->initializeMethodCallCount();
    }

    /**
     * @inheritDoc
     */
    public function handle(Repository $repository, Collection $pullRequests): void
    {
        $this->parameters = [
            'repository' => $repository,
            'pullRequests' => $pullRequests,
        ];

        $this->methodCallCount['handle']++;
    }
}
