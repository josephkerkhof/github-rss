<?php

namespace App\Domains\PullRequests\Schema;

use Spatie\LaravelData\Data;

final class AuthorData extends Data
{
    public function __construct(
        public readonly string $username,
        public readonly string $profileUrl,
    ) {
    }
}
