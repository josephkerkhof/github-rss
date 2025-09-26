<?php

declare(strict_types=1);

namespace App\Providers;

use App\Common\Contracts\GitHubRetrieverInterface;
use App\Common\GitHubRetriever;
use App\Domains\PullRequests\Mappers\Contracts\GitHubIssueResponseToIssueDataMapperInterface;
use App\Domains\PullRequests\Mappers\Contracts\GitHubPullRequestResponseToPullRequestDataMapperInterface;
use App\Domains\PullRequests\Mappers\GitHubIssueResponseToIssueDataMapper;
use App\Domains\PullRequests\Mappers\GitHubPullRequestResponseToPullRequestDataMapper;
use Carbon\CarbonImmutable;
use Date;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerAppInterfaces();
        $this->registerTelescope();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Carbon Immutable for timestamps
        Date::use(CarbonImmutable::class);
    }

    private function registerAppInterfaces(): void
    {
        $this->app->bind(GitHubRetrieverInterface::class, GitHubRetriever::class);
        $this->app->bind(GitHubIssueResponseToIssueDataMapperInterface::class, GitHubIssueResponseToIssueDataMapper::class);
        $this->app->bind(GitHubPullRequestResponseToPullRequestDataMapperInterface::class, GitHubPullRequestResponseToPullRequestDataMapper::class);
    }

    private function registerTelescope(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
