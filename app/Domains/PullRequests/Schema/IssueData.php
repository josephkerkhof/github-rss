<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Schema;

use Spatie\LaravelData\Data;

final class IssueData extends Data
{
    public function __construct(
        public readonly int $number,
        public readonly string $htmlUrl
    ) {
    }
}
