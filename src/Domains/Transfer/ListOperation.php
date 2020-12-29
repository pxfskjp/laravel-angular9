<?php

namespace App\Domains\Transfer;

use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\TransferRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

final class ListOperation extends AbstractOperation
{

    /**
     *
     * @var TransferRepositoryInterface $transferRepository
     */
    private TransferRepositoryInterface $transferRepository;

    /**
     *
     * @param TransferRepositoryInterface $transferRepository
     */
    public function __construct(TransferRepositoryInterface $transferRepository)
    {
        $this->transferRepository = $transferRepository;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Domains\OperationInterface::handle()
     */
    public function handle(?User $uthUser): JsonResponse
    {
        try {
            $transfers = $this->transferRepository->list();
            return $this->runResponse(new RespondSuccessJson('success', $transfers));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd pobierania listy'));
        }
    }
}
