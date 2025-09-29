<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Domains\PullRequests\Retrievers\Contracts\PullRequestRetrieverInterface;
use App\Domains\PullRequests\Schema\AuthorData;
use App\Domains\PullRequests\Schema\IssueData;
use App\Domains\PullRequests\Schema\PullRequestData;
use App\Models\Repository;
use Carbon\CarbonImmutable;
use Tests\Doubles\Concerns\InitializesCallCounts;

final class PullRequestRetrieverSpy implements PullRequestRetrieverInterface
{
    use InitializesCallCounts;

    public array $parameters;

    public array $methodCallCount;

    public function __construct()
    {
        $this->methodCallCount = $this->initializeMethodCallCount();
    }

    public function retrieve(Repository $repository, IssueData $issue): PullRequestData
    {
        $this->parameters = [
            'repository' => $repository,
            'issue' => $issue,
        ];

        $this->methodCallCount['retrieve']++;

        return new PullRequestData(
            new AuthorData('test-author-username', 'https://example.com/profile-url'),
            '12.x',
            12345,
            'Test Pull Request',
            'Test Description',
            'https://example.com/pull-request-url',
            CarbonImmutable::parse('2024-12-31 12:31:30')
        );
    }
}
