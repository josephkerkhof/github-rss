<?php

namespace App\Console\Commands;

use App\Models\GitHubUser;
use App\Models\Label;
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
        $mergedPullRequests = GitHub::search()->issues(
            'repo:laravel/framework is:pr is:merged',
        );

        $mergedPullRequestsUrls = collect($mergedPullRequests['items'])->pluck('pull_request.html_url');

        foreach ($mergedPullRequestsUrls as $url) {
            $id = Str::of($url)->afterLast('/')->toString();

            $pullRequestDetails = GitHub::pullRequests()->show('laravel', 'framework', $id);
            dd($pullRequestDetails);
        }

        return Command::SUCCESS;
    }
}
