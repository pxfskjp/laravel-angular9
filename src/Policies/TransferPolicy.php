<?php

namespace App\Policies;

use App\Data\Models\Transfer;
use App\Data\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class TransferPolicy
{
    use HandlesAuthorization;

    /**
     *
     * @param User $authority
     * @param Transfer $transfer
     * @return bool
     */
    public function store(User $authority, ?Transfer $transfer = null): bool
    {
        return true;
    }

    /**
     *
     * @param User $authority
     * @param Transfer $transfer
     * @return bool
     */
    public function update(User $authority, ?Transfer $transfer = null): bool
    {
        return true;
    }

    /**
     *
     * @param User $authority
     * @param Transfer $transfer
     * @return bool
     */
    public function delete(User $authority, ?Transfer $transfer = null): bool
    {
        return true;
    }
}
