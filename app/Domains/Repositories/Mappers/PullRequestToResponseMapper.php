<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Mappers;

use App\Domains\Repositories\Mappers\Concerns\PullRequestToResponseMapperInterface;
use App\Domains\Repositories\Schema\Responses\PullRequestData;
use App\Models\Author;
use App\Models\Branch;
use App\Models\PullRequest;

final readonly class PullRequestToResponseMapper implements PullRequestToResponseMapperInterface
{
    /**
     * @param PullRequest&object{
     *     branch: Branch,
     *     author: Author,
     * } $pullRequest
     * @return PullRequestData
     */
    public function map(PullRequest $pullRequest): PullRequestData
    {
        return new PullRequestData(
            $pullRequest->id,
            $pullRequest->title,
            $pullRequest->body,
            (int) $pullRequest->number,
            $pullRequest->branch->name,
            $pullRequest->author->username,
            $pullRequest->author->profile_url,
            $pullRequest->url,
            $pullRequest->merged_at,
        );
    }
}
