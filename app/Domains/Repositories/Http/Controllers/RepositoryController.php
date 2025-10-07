<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Http\Controllers;

use App\Domains\Repositories\Schema\Responses\RepositoryData;
use App\Http\Controllers\Controller;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
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
}
