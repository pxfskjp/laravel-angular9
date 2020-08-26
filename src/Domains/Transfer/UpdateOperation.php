<?php

namespace App\Domains\Transfer;

use App\Data\Models\Transfer;
use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Domains\Transfer\Validators\StoreValidator;
use App\Http\Responses\RespondBadRequestJson;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\TransferRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class UpdateOperation extends AbstractOperation
{

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
    public function handle(?User $authUser): JsonResponse
    {
        try {
            Gate::authorize('update', Transfer::class);
            $input = $this->requestData(
                [
                    'user_id' => '',
                    'hardware_id' => '',
                    'type' => '',
                    'date' => '',
                    'remarks' => '',
                ],
                true
            );
            if ($response = $this->validateWithResponse(StoreValidator::class, $input)) {
                return $response;
            }
            $transfer = DB::transaction(function () use ($input) {
                $transfer = $this->transferRepository->getById($input['id']);
                $this->transferRepository->update($transfer, $input);
                return $transfer;
            });
            return $this->runResponse(new RespondSuccessJson('success', $transfer));
        } catch (ModelNotFoundException $e) {
            return $this->runResponse(new RespondBadRequestJson('Nie znaleziono transfer'));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd dodawania transfer'));
        }
    }
}
