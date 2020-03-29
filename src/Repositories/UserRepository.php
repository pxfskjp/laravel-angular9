<?php

namespace App\Repositories;

use App\Data\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Collection;

final class UserRepository implements UserRepositoryInterface
{

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\UserRepositoryInterface::list()
     */
    public function list(): Collection
    {
        return User::with('hardware')
            ->get();
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\UserRepositoryInterface::store()
     */
    public function store(array $attributes): User
    {
        return User::create($attributes);
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\UserRepositoryInterface::update()
     */
    public function update(User $user, array $attributes): void
    {
        $user->update($attributes);
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\UserRepositoryInterface::delete()
     */
    public function delete(User $user): void
    {
        $user->delete();
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\UserRepositoryInterface::getById()
     */
    public function getById(int $id): User
    {
        return User::findOrFail($id);
    }
}
