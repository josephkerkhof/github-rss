<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Common\Contracts\GitHubRetrieverInterface;
use Tests\Doubles\Traits\InitializesCallCounts;

final class GitHubRetrieverSpy implements GitHubRetrieverInterface
{
    use InitializesCallCounts;

    public array $parameters;

    public array $methodCallCount;

    public function __construct()
    {
        $this->methodCallCount = $this->initializeMethodCallCount();
    }

    public function retrieveIssues(string $query, string $sort = 'updated', string $order = 'desc'): array
    {
        $this->parameters = [
            'query' => $query,
            'sort' => $sort,
            'order' => $order,
        ];

        $this->methodCallCount['retrieveIssues']++;

        return [
            'items' => []
        ];
    }

    public function retrievePullRequest(string $owner, string $repo, int $number): array
    {
        $this->parameters = [
            'owner' => $owner,
            'repo' => $repo,
            'number' => $number,
        ];

        $this->methodCallCount['retrievePullRequest']++;

        return [
            'items' => []
        ];
    }
}
