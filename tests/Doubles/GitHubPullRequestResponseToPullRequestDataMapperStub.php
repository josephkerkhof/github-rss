<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Domains\PullRequests\Mappers\Contracts\GitHubPullRequestResponseToPullRequestDataMapperInterface;
use App\Domains\PullRequests\Schema\AuthorData;
use App\Domains\PullRequests\Schema\PullRequestData;
use Carbon\CarbonImmutable;

class GitHubPullRequestResponseToPullRequestDataMapperStub implements GitHubPullRequestResponseToPullRequestDataMapperInterface
{

    public function map(array $input): PullRequestData
    {
        return new PullRequestData(
            new AuthorData(
                'muh_username',
                'https://example.com/profile-url',
            ),
            '12.x',
            54321,
            'Muh Pull Request',
            'Muh Description',
            'https://example.com/pull-request-url',
            CarbonImmutable::parse('2024-12-31 12:31:30')
        );
    }
}
