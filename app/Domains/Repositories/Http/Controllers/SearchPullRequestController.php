<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Http\Controllers;

use App\Domains\Repositories\Mappers\Concerns\PullRequestToResponseMapperInterface;
use App\Domains\Repositories\Schema\Requests\SearchPullRequestRequestData;
use App\Http\Controllers\Controller;
use App\Models\PullRequest;
use App\Models\Repository;
use Gate;
use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;

final class SearchPullRequestController extends Controller
{
    public function __construct(
        private readonly PullRequestToResponseMapperInterface $mapper,
    ) {
    }

    public function __invoke(
        Repository $repository,
        SearchPullRequestRequestData $request,
    ): CursorPaginator {
        Gate::authorize('view', $repository);

        $query = PullRequest::query()
            ->whereBelongsTo($repository)
            ->orderBy('id', 'desc');

        if (! empty($request->numbers)) {
            $query->whereIn('number', $request->numbers);
        }

        if (! empty($request->githubUsernames)) {
            $query->whereHas('author', fn ($authors) => $authors->whereIn('username', $request->githubUsernames));
        }

        if (! empty($request->branchUuids)) {
            $query->whereHas('branch', fn ($branches) => $branches->whereIn('uuid', $request->branchUuids));
        }

        $cursor = $request->cursor ? Cursor::fromEncoded($request->cursor) : null;

        // Apply cursor constraints
        if ($cursor) {
            $query->where('id', '<', $cursor->parameter('id'));
        }

        // Fetch one extra item to check if there are more pages
        $items = $query->limit($request->perPage + 1)->get();

        return new CursorPaginator(
            $items,
            $request->perPage,
            $cursor,
            [
                'path' => request()->url(),
                'cursorName' => 'cursor',
                'parameters' => ['id'],
            ]
        )->through($this->mapper->map(...));
    }
}
