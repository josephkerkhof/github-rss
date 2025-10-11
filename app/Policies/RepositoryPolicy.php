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

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Repository $repository): bool
    {
        return $user->id === $repository->user_id;
    }

    public function delete(User $user, Repository $repository): bool
    {
        return $user->id === $repository->user_id;
    }
}
