<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\PullRequests\Commands\Contracts\CreatePullRequestCommandInterface;
use App\Domains\PullRequests\Commands\CreatePullRequestCommand;
use App\Domains\PullRequests\Retrievers\Contracts\IssueRetrieverInterface;
use App\Domains\PullRequests\Retrievers\Contracts\PullRequestRetrieverInterface;
use App\Domains\PullRequests\Retrievers\IssueRetriever;
use App\Domains\PullRequests\Retrievers\PullRequestRetriever;
use App\Models\Repository;
use App\Policies\RepositoryPolicy;
use Gate;
use Override;
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
    public array $bindings = [
        CreatePullRequestCommandInterface::class => CreatePullRequestCommand::class,
        GitHubRetrieverInterface::class => GitHubRetriever::class,
        GitHubIssueResponseToIssueDataMapperInterface::class => GitHubIssueResponseToIssueDataMapper::class,
        GitHubPullRequestResponseToPullRequestDataMapperInterface::class => GitHubPullRequestResponseToPullRequestDataMapper::class,
        IssueRetrieverInterface::class => IssueRetriever::class,
        PullRequestRetrieverInterface::class => PullRequestRetriever::class,
    ];

    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->registerTelescope();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Carbon Immutable for timestamps
        Date::use(CarbonImmutable::class);

        $this->registerPolicies();
    }

    private function registerTelescope(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    private function registerPolicies(): void
    {
        Gate::policy(Repository::class, RepositoryPolicy::class);
    }
}
