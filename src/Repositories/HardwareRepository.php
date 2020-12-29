<?php

namespace App\Repositories;

use App\Data\Models\Hardware;
use App\Repositories\Interfaces\HardwareRepositoryInterface;
use Illuminate\Support\Collection;

final class HardwareRepository implements HardwareRepositoryInterface
{

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\HardwareRepositoryInterface::list()
     */
    public function list(): Collection
    {
        return Hardware::with(
            [
                'system',
                'user'
            ]
        )
            ->get();
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\HardwareRepositoryInterface::store()
     */
    public function store(array $attributes): Hardware
    {
        $hardware = Hardware::create($attributes);
        if (! empty($attributes['system_id'])) {
            $hardware->system()
                ->create(
                    [
                        'system_id' => $attributes['system_id']
                    ]
                );
            $hardware->load('system');
        }
        return $hardware;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\HardwareRepositoryInterface::update()
     */
    public function update(Hardware $hardware, array $attributes): void
    {
        if (empty($attributes['system_id'])) {
            $hardware->system()->delete();
        }
        $hardware->update($attributes);
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\HardwareRepositoryInterface::delete()
     */
    public function delete(Hardware $hardware): void
    {
        $hardware->delete();
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\HardwareRepositoryInterface::getById()
     */
    public function getById(int $id): Hardware
    {
        return Hardware::findOrFail($id);
    }
}
