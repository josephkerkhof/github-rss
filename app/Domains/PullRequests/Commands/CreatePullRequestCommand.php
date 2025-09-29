<?php

declare(strict_types=1);

namespace App\Domains\PullRequests\Commands;

use App\Domains\PullRequests\Commands\Contracts\CreatePullRequestCommandInterface;
use App\Domains\PullRequests\Schema\AuthorData;
use App\Domains\PullRequests\Schema\PullRequestData;
use App\Models\Author;
use App\Models\Branch;
use App\Models\PullRequest;
use App\Models\Repository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Log;
use LogicException;
use Throwable;

final readonly class CreatePullRequestCommand implements CreatePullRequestCommandInterface
{
    public function __construct(
        private ConnectionInterface $connection,
    ) {
    }

    /**
     * @param Collection<int, PullRequestData> $pullRequests
     * @throws Throwable When a database error occurs
     */
    public function handle(Repository $repository, Collection $pullRequests): void
    {
        if ($pullRequests->isEmpty()) {
            Log::info("No pull requests to create for repository: {$repository->name}");

            return;
        }

        $this->connection->transaction(function () use ($repository, $pullRequests): void {
            $branchesKeyedByPullRequest = $this->branchesKeyedByPullRequest($repository, $pullRequests);
            $authorsKeyedByPullRequest = $this->authorsKeyedByPullRequest($pullRequests);

            $pullRequestsToCreate = $pullRequests->map(function (PullRequestData $pullRequest) use ($repository, $branchesKeyedByPullRequest, $authorsKeyedByPullRequest) {
                $author = $authorsKeyedByPullRequest[$pullRequest->number];
                $branch = $branchesKeyedByPullRequest[$pullRequest->number];

                return [
                    'repository_id' => $repository->id,
                    'branch_id' => $branch->id,
                    'author_id' => $author->id,
                    'number' => $pullRequest->number,
                    'title' => $pullRequest->title,
                    'body' => $pullRequest->body,
                    'url' => $pullRequest->url,
                    'merged_at' => $pullRequest->mergedAt,
                ];
            });

            PullRequest::query()->fillAndInsert($pullRequestsToCreate->toArray());

            Log::info("Created {$pullRequestsToCreate->count()} pull requests for repository: {$repository->name}");
        });
    }

    /**
     * @param Collection<int, PullRequestData> $pullRequests
     */
    private function branchesKeyedByPullRequest(Repository $repository, Collection $pullRequests): Collection
    {
        $targetBranchNames = $pullRequests->pluck('targetBranchName')->unique()->values();
        $alreadyExistingBranches = Branch::query()
            ->whereBelongsTo($repository)
            ->whereIn('name', $targetBranchNames)
            ->pluck('name');

        // Create branches that don't exist yet
        $branchesToCreate = $targetBranchNames
            ->reject(fn (string $branchName) => $alreadyExistingBranches->contains($branchName))
            ->map(fn (string $branchName) => [
                'repository_id' => $repository->id,
                'name' => $branchName,
            ]);
        if ($branchesToCreate->isNotEmpty()) {
            Branch::query()->fillAndInsert($branchesToCreate->toArray());
        }

        // Refetch the branches to load the newly created ones
        $allBranches = Branch::query()
            ->whereBelongsTo($repository)
            ->whereIn('name', $targetBranchNames)
            ->get();

        return $pullRequests
            ->mapWithKeys(function (PullRequestData $pullRequest) use ($allBranches) {
                $branch = $allBranches->firstWhere('name', $pullRequest->targetBranchName);

                if (is_null($branch)) {
                    $message = "Branch not found for pull request: {$pullRequest->number}.";
                    Log::warning("$message Halting.");

                    throw new LogicException($message);
                }

                return [$pullRequest->number => $branch];
            });
    }

    private function authorsKeyedByPullRequest(Collection $pullRequests): Collection
    {
        /** @var Collection<int, AuthorData> $allAuthors */
        $allAuthors = $pullRequests->pluck('author')->unique()->values();

        $authorsThatAlreadyExist = Author::query()
            ->whereIn('username', $allAuthors->pluck('username'))
            ->pluck('username');

        $authorsToCreate = $allAuthors
            ->reject(fn (AuthorData $author) => $authorsThatAlreadyExist->contains($author->username))
            ->map(fn (AuthorData $author) => [
                'username' => $author->username,
                'profile_url' => $author->profileUrl,
            ]);

        if ($authorsToCreate->isNotEmpty()) {
            Author::query()->fillAndInsert($authorsToCreate->toArray());
        }

        // Refetch the authors to load the newly created ones
        $allAuthors = Author::query()
            ->whereIn('username', $allAuthors->pluck('username'))
            ->get();

        return $pullRequests
            ->mapWithKeys(function (PullRequestData $pullRequest) use ($allAuthors) {
                $author = $allAuthors->firstWhere('username', $pullRequest->author->username);
                if (is_null($author)) {
                    $message = "Author not found for pull request: {$pullRequest->number}.";
                    Log::warning("$message Halting.");

                    throw new LogicException($message);
                }

                return [$pullRequest->number => $author];
            });
    }
}
