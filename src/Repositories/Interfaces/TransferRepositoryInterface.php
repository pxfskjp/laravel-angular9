<?php

namespace App\Repositories\Interfaces;

use App\Data\Models\Transfer;
use Illuminate\Support\Collection;

interface TransferRepositoryInterface
{

    /**
     *
     * @return Collection
     */
    public function list(): Collection;

    /**
     *
     * @param array $attributes
     * @return Transfer
     */
    public function store(array $attributes): Transfer;

    /**
     *
     * @param Transfer $transfer
     * @param array $attributes
     */
    public function update(Transfer $transfer, array $attributes): void;

    /**
     *
     * @param Transfer $transfer
     */
    public function delete(Transfer $transfer): void;

    /**
     *
     * @param int $id
     * @return Transfer
     */
    public function getById(int $id): Transfer;
}
