<?php

namespace App\Repositories;

use App\Data\Models\System;
use App\Repositories\Interfaces\SystemRepositoryInterface;
use Illuminate\Support\Collection;

final class SystemRepository implements SystemRepositoryInterface
{

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\SystemRepositoryInterface::list()
     */
    public function list(): Collection
    {
        return System::all();
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\SystemRepositoryInterface::store()
     */
    public function store(array $attributes): System
    {
        return System::create($attributes);
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\SystemRepositoryInterface::update()
     */
    public function update(System $system, array $attributes): void
    {
        $system->update($attributes);
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\SystemRepositoryInterface::delete()
     */
    public function delete(System $system): void
    {
        $system->delete();
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\SystemRepositoryInterface::getById()
     */
    public function getById(int $id): System
    {
        return System::findOrFail($id);
    }
}
