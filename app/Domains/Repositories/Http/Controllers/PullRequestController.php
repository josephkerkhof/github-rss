<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Http\Controllers;

use App\Domains\Repositories\Mappers\Concerns\PullRequestToResponseMapperInterface;
use App\Http\Controllers\Controller;
use App\Models\Repository;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;

final class PullRequestController extends Controller
{
    public function __construct(
        private readonly PullRequestToResponseMapperInterface $mapper,
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    public function index(Repository $repository) {
        Gate::authorize('view', $repository);

        return $repository
            ->pullRequests()
            ->with([
                'author',
                'branch',
            ])
            ->latest('id')
            ->paginate(10)
            ->through($this->mapper->map(...)); // @phpstan-ignore-line
    }
}
