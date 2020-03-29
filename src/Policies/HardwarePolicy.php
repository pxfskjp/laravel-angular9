<?php

namespace App\Policies;

use App\Data\Models\Hardware;
use App\Data\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class HardwarePolicy
{
    use HandlesAuthorization;

    /**
     *
     * @param User $authority
     * @param Hardware $hardware
     * @return bool
     */
    public function store(User $authority, ?Hardware $hardware = null): bool
    {
        return true;
    }

    /**
     *
     * @param User $authority
     * @param Hardware $hardware
     * @return bool
     */
    public function update(User $authority, ?Hardware $hardware = null): bool
    {
        return true;
    }

    /**
     *
     * @param User $authority
     * @param Hardware $hardware
     * @return bool
     */
    public function delete(User $authority, ?Hardware $hardware = null): bool
    {
        return true;
    }
}
