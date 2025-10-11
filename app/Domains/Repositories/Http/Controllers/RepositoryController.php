<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Http\Controllers;

use App\Domains\Repositories\Schema\Responses\RepositoryData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Repositories\StoreRepositoryRequest;
use App\Http\Requests\Repositories\UpdateRepositoryRequest;
use App\Models\Repository;
use App\Models\User;
use Gate;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class RepositoryController extends Controller
{
    /**
     * @param User $user
     * @return Collection<int, RepositoryData>
     */
    public function index(
        #[CurrentUser]
        User $user,
    ): Collection {
        /** @var Collection<int, Repository> $repositories */
        $repositories = $user->repositories;

        return $repositories->map(fn (Repository $repository) => new RepositoryData(
            $repository->uuid,
            $repository->name,
            sprintf('%s/%s', $repository->owner, $repository->repo),
        ));
    }

    public function store(
        StoreRepositoryRequest $request,
        #[CurrentUser]
        User $user,
    ): JsonResponse {
        Gate::authorize('create', Repository::class);

        /** @var Repository $repository */
        $repository = $user->repositories()->create($request->validated());

        return response()->json(new RepositoryData(
            $repository->uuid,
            $repository->name,
            sprintf('%s/%s', $repository->owner, $repository->repo),
        ), 201);
    }

    public function update(
        UpdateRepositoryRequest $request,
        Repository $repository,
    ): JsonResponse {
        Gate::authorize('update', $repository);

        $repository->update($request->validated());

        return response()->json(new RepositoryData(
            $repository->uuid,
            $repository->name,
            sprintf('%s/%s', $repository->owner, $repository->repo),
        ));
    }

    public function destroy(Repository $repository): JsonResponse
    {
        Gate::authorize('delete', $repository);

        $repository->delete();

        return response()->json(null, 204);
    }
}
