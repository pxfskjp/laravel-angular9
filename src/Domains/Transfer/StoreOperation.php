<?php

namespace App\Domains\Transfer;

use App\Data\Models\Transfer;
use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Domains\Transfer\Validators\StoreValidator;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\TransferRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

final class StoreOperation extends AbstractOperation
{

    /**
     *
     * @var TransferRepositoryInterface $transferRepository
     */
    private $transferRepository;

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
    public function handle(?User $authUser): JsonResponse
    {
        try {
            Gate::authorize('store', Transfer::class);
            $input = $this->requestData(
                [
                    'user_id',
                    'hardware_id',
                    'type',
                    'date',
                    'remarks',
                ]
            );
            if ($response = $this->validateWithResponse(StoreValidator::class, $input)) {
                return $response;
            }
            $transfer = $this->transferRepository->store($input);
            return $this->runResponse(new RespondSuccessJson('success', $transfer->toArray()));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd dodawania transfer'));
        }
    }
}
