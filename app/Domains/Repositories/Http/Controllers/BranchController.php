<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Http\Controllers;

use App\Domains\Repositories\Schema\Responses\BranchData;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Repository;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;

class BranchController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(
        Repository $repository,
    ): Collection {
        Gate::authorize('view', $repository);

        /** @var Collection<int, Branch> $branches */
        $branches = $repository->branches;

        return $branches->map(fn (Branch $branch) => new BranchData(
            $branch->uuid,
            $branch->name,
        ));
    }
}
