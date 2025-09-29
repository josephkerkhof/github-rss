<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Commands;

use App\Domains\PullRequests\Commands\Contracts\CreatePullRequestCommandInterface;
use App\Domains\PullRequests\Schema\PullRequestData;
use App\Models\Author;
use App\Models\Branch;
use App\Models\Repository;
use Illuminate\Support\Collection;

final readonly class CreatePullRequestCommand implements CreatePullRequestCommandInterface
{
    /**
     * @param Collection<int, PullRequestData> $pullRequests
     */
    public function handle(Repository $repository, Collection $pullRequests): void
    {
        // TODO refactor this to not loop
        $pullRequests->each(function(PullRequestData $pullRequest) use ($repository): void {
            /** @var Branch $branch */
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
