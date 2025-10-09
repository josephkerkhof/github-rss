<?php

namespace App\Domains\Repositories\Schema\Responses;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

/**
 * @codeCoverageIgnore
 */
final class PullRequestData extends Data
{
    public function __construct(
        public string $title,
        public ?string $body,
        public int $number,
        public string $branch,
        public string $authorUsername,
        public string $authorProfileUrl,
        public string $url,
        public CarbonImmutable $mergedAt,
    ) {
    }
}
