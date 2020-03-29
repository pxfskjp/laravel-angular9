<?php

namespace App\Repositories\Interfaces;

use App\Data\Models\Hardware;
use Illuminate\Support\Collection;

interface HardwareRepositoryInterface
{

    /**
     *
     * @return Collection
     */
    public function list(): Collection;

    /**
     *
     * @param array $attributes
     * @return Hardware
     */
    public function store(array $attributes): Hardware;

    /**
     *
     * @param Hardware $hardware
     * @param array $attributes
     */
    public function update(Hardware $hardware, array $attributes): void;

    /**
     *
     * @param Hardware $hardware
     */
    public function delete(Hardware $hardware): void;

    /**
     *
     * @param int $id
     * @return Hardware
     */
    public function getById(int $id): Hardware;
}
