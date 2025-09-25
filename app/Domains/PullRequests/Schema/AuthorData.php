<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Schema;

use Spatie\LaravelData\Data;

/**
 * @codeCoverageIgnore
 */
final class AuthorData extends Data
{
    public function __construct(
        public readonly string $username,
        public readonly string $profileUrl,
    ) {
    }
}
