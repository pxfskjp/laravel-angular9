<?php

namespace App\Policies;

use App\Data\Models\System;
use App\Data\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class SystemPolicy
{
    use HandlesAuthorization;

    /**
     *
     * @param User $authority
     * @param System $system
     * @return bool
     */
    public function store(User $authority, ?System $system = null): bool
    {
        return true;
    }

    /**
     *
     * @param User $authority
     * @param System $system
     * @return bool
     */
    public function update(User $authority, ?System $system = null): bool
    {
        return true;
    }

    /**
     *
     * @param User $authority
     * @param System $system
     * @return bool
     */
    public function delete(User $authority, ?System $system = null): bool
    {
        return true;
    }
}
