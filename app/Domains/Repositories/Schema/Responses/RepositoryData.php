<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Schema\Responses;

use Spatie\LaravelData\Data;

final class RepositoryData extends Data
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $name,
        public readonly string $slug,
    ) {
    }
}
