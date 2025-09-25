<?php

declare(strict_types=1);

namespace App\Common;

use App\Common\Contracts\GitHubRetrieverInterface;
use GrahamCampbell\GitHub\Facades\GitHub;

final readonly class GitHubRetriever implements GitHubRetrieverInterface
{
    public function retrieveIssues(string $query, string $sort = 'updated', string $order = 'desc'): array
    {
        // @phpstan-ignore-next-line - GitHub facade is not typed correctly
        return GitHub::search()->issues(
            $query,
            $sort,
            $order
        );
    }

    public function retrievePullRequest(string $owner, string $repo, int $number): array
    {
        // @phpstan-ignore-next-line - GitHub facade is not typed correctly
        return GitHub::pullRequests()->show($owner, $repo, $number);
    }
}
