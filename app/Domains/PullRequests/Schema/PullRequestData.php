<?php

namespace App\Domains\PullRequests\Schema;

use Spatie\LaravelData\Data;

final class PullRequestData extends Data
{
    public function __construct(
        public readonly AuthorData $author,
        public readonly string $targetBranchName,
        public readonly int $number,
        public readonly string $title,
        public readonly string $body,
        public readonly string $url,
        public readonly string $mergedAt,
    ) {
    }
}
