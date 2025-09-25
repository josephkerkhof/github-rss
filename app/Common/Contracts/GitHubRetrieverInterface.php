<?php

declare(strict_types=1);

namespace App\Common\Contracts;

interface GitHubRetrieverInterface
{
    public function retrieveIssues(string $query, string $sort = 'updated', string $order = 'desc'): array;

    public function retrievePullRequest(string $owner, string $repo, int $number): array;
}
