<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class RepositoryPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Repository $repository): bool
    {
        return $user->id === $repository->user_id;
    }
}
