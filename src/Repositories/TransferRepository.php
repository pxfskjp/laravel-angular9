<?php

namespace App\Repositories;

use App\Data\Models\Transfer;
use App\Repositories\Interfaces\TransferRepositoryInterface;
use Illuminate\Support\Collection;

final class TransferRepository implements TransferRepositoryInterface
{

    /**
     *
     * @return Collection
     */
    public function list(): Collection
    {
        return Transfer::join('hardwares', 'hardwares.id', '=', 'transfers.hardware_id')
            ->get(
                [
                    'transfers.*',
                    'hardwares.name as hardware_name'
                ]
            );
    }

    /**
     *
     * @param array $attributes
     * @return Transfer
     */
    public function store(array $attributes): Transfer
    {
        $transfer = Transfer::create($attributes);
        if ($transfer->isLeaseType()) {
            $transfer->user()->create(
                [
                    'user_id' => $transfer->user_id,
                    'hardware_id' => $transfer->hardware_id
                ]
            );
        } else {
            $transfer->user()->delete();
        }
        return $transfer;
    }

    /**
     *
     * @param Transfer $transfer
     * @param array $attributes
     */
    public function update(Transfer $transfer, array $attributes): void
    {
        $transfer->update($attributes);
    }

    /**
     *
     * @param Transfer $transfer
     */
    public function delete(Transfer $transfer): void
    {
        $transfer->delete();
    }

    /**
     *
     * @param int $id
     * @return Transfer
     */
    public function getById(int $id): Transfer
    {
        return Transfer::findOrFail($id);
    }
}
