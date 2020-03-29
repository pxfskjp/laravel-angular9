<?php

namespace App\Domains\User\Transformers;

use App\Data\Models\User;
use Illuminate\Support\Collection;

final class UserTransformer
{
    /**
     *
     * @param Collection $users
     * @return array
     */
    public function transform(Collection $users): array
    {
        $users->map(function ($user) {
            return $this->transformUser($user);
        });
        return $users->toArray();
    }

    /**
     *
     * @param User $user
     * @return array
     */
    public function transformUser(User $user): array
    {
        return $user->toArray() + [
            'hardware_id' => ($user->hardware) ? $user->hardware->hardware_id : null,
        ];
    }
}
