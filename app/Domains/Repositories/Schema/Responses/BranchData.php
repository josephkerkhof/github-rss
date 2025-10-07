<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Schema\Responses;

use Spatie\LaravelData\Data;

final class BranchData extends Data
{
    public function __construct(
        public string $uuid,
        public string $name,
    ) {
    }
}
