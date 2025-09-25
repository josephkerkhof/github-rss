<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Common\Contracts\GitHubRetrieverInterface;
use Tests\Doubles\Traits\InitializesCallCounts;

final class GitHubRetrieverSpy implements GitHubRetrieverInterface
{
    use InitializesCallCounts;

    public string $query;
    public string $sort;
    public string $order;
    public array $methodCallCount;

    public function __construct()
    {
        $this->methodCallCount = $this->initializeMethodCallCount();
    }

    public function retrieveIssues(string $query, string $sort = 'updated', string $order = 'desc'): array
    {
        $this->query = $query;
        $this->sort = $sort;
        $this->order = $order;

        $this->methodCallCount['retrieveIssues']++;

        return [
            'items' => []
        ];
    }
}
