<?php

namespace App\Repositories\Interfaces;

use App\Data\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{

    /**
     *
     * @return Collection
     */
    public function list(): Collection;

    /**
     *
     * @param array $attributes
     * @return User
     */
    public function store(array $attributes): User;

    /**
     *
     * @param User $user
     * @param array $attributes
     */
    public function update(User $user, array $attributes): void;

    /**
     *
     * @param User $user
     */
    public function delete(User $user): void;

    /**
     *
     * @param int $id
     * @return User
     */
    public function getById(int $id): User;
}
