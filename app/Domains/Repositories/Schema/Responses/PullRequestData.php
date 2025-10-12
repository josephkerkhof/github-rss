<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Schema\Responses;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * @codeCoverageIgnore
 */
#[MapName(SnakeCaseMapper::class)]
final class PullRequestData extends Data
{
    public function __construct(
        #[Hidden]
        public int $id,
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
