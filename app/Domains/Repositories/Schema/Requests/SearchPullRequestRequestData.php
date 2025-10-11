<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Schema\Requests;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[MergeValidationRules]
#[MapName(SnakeCaseMapper::class)]
final class SearchPullRequestRequestData extends Data
{
    public function __construct(
        public readonly ?array $githubUsernames,
        public readonly ?array $branchUuids,
        public readonly ?array $numbers,
        public readonly ?string $cursor,
        public readonly int $perPage,
    ) {
    }

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'githubUsernames.*' => ['string'],
            'branchUuids.*' => ['string', 'uuid'],
            'numbers.*' => ['integer'],
        ];
    }
}
