<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Models\Branch;
use App\Models\PullRequest;
use App\Models\Repository;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Console\Command;
use Str;

class FetchPullRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-pull-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the latest pull requests from GitHub and stores them in the database.';

    /**
     * Execute the console command.
     */
    public function handle(): bool
    {
        Repository::all()->chunk(100)->each(function ($chunk) {
            foreach ($chunk as $repository) {
                $this->fetchByRepository($repository);
            }
        });

        return Command::SUCCESS;
    }

    private function fetchByRepository(Repository $repository): void
    {
        $this->info("Fetching merged pull requests for repository: {$repository->name}");

        $pullRequests = GitHub::search()->issues(
            "repo:{$repository->slug} is:pr is:merged",
        );
        $mergedPullRequestsUrls = collect($pullRequests['items'])->pluck('pull_request.html_url');

        foreach ($mergedPullRequestsUrls as $url) {
            $this->fetchMergeRequestByUrl($repository, $url);
        }
    }

    private function fetchMergeRequestByUrl(Repository $repository, string $url): void
    {
        $number = Str::of($url)->afterLast('/')->toString();

        $pullRequestAlreadyExists = PullRequest::query()
            ->whereBelongsTo($repository)
            ->where('number', $number)
            ->exists();

        if ($pullRequestAlreadyExists) {
            $this->info("Pull request #{$number} already exists. Skipping.");

            return;
        }

        $pullRequestDetails = GitHub::pullRequests()->show('laravel', 'framework', $number);

        $branch = Branch::firstOrCreate(
            [
                'repository_id' => $repository->id,
                'name' => $pullRequestDetails['base']['ref'],
            ]
        );

        $author = Author::firstOrCreate(
            [
                'username' => $pullRequestDetails['user']['login']
            ],
            [
                'profile_url' => $pullRequestDetails['user']['html_url'],
            ]
        );

        PullRequest::create([
            'repository_id' => $repository->id,
            'branch_id' => $branch->id,
            'author_id' => $author->id,
            'number' => $number,
            'title' => $pullRequestDetails['title'],
            'body' => $pullRequestDetails['body'],
            'url' => $pullRequestDetails['html_url'],
            'merged_at' => $pullRequestDetails['merged_at'],
        ]);

        $this->info("Pull request #{$number} fetched successfully.");
    }
}
