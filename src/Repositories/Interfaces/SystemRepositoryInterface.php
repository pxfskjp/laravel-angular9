<?php

namespace App\Repositories\Interfaces;

use App\Data\Models\System;
use Illuminate\Support\Collection;

interface SystemRepositoryInterface
{

    /**
     *
     * @return Collection
     */
    public function list(): Collection;

    /**
     *
     * @param array $attributes
     * @return System
     */
    public function store(array $attributes): System;

    /**
     *
     * @param System $system
     * @param array $attributes
     */
    public function update(System $system, array $attributes): void;

    /**
     *
     * @param System $system
     */
    public function delete(System $system): void;

    /**
     *
     * @param int $id
     * @return System
     */
    public function getById(int $id): System;
}
