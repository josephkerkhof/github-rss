<?php

namespace App\Domains\PullRequests\Commands;

use App\Domains\PullRequests\Schema\PullRequestData;
use App\Models\Author;
use App\Models\Repository;
use Illuminate\Support\Collection;

final readonly class CreatePullRequestCommand
{
    /**
     * @param Collection<int, PullRequestData> $pullRequests
     */
    public function handle(Repository $repository, Collection $pullRequests): void
    {
        $pullRequests->each(function(PullRequestData $pullRequest) use ($repository) {
            $branch = $repository->branches()->firstOrCreate([
                'name' => $pullRequest->targetBranchName,
            ]);

            $author = Author::query()->firstOrCreate(
                [
                    'username' => $pullRequest->author->username,
                ],
                [
                    'profile_url' => $pullRequest->author->profileUrl,
                ]
            );

            $repository->pullRequests()->create([
                'branch_id' => $branch->id,
                'author_id' => $author->id,
                'number' => $pullRequest->number,
                'title' => $pullRequest->title,
                'body' => $pullRequest->body,
                'url' => $pullRequest->url,
                'merged_at' => $pullRequest->mergedAt,
            ]);
        });
    }
}
