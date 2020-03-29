<?php

namespace App\Policies;

use App\Data\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserPolicy
{
    use HandlesAuthorization;

    /**
     *
     * @param User $authority
     * @param User $user
     * @return bool
     */
    public function store(User $authority, ?User $user = null): bool
    {
        return true;
    }

    /**
     *
     * @param User $authority
     * @param User $user
     * @return bool
     */
    public function update(User $authority, ?User $user = null): bool
    {
        return true;
    }

    /**
     *
     * @param User $authority
     * @param User $user
     * @return bool
     */
    public function delete(User $authority, ?User $user = null): bool
    {
        return true;
    }
}
