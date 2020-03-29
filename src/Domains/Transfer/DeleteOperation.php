<?php

namespace App\Domains\Transfer;

use App\Data\Models\Transfer;
use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Http\Responses\RespondBadRequestJson;
use App\Http\Responses\RespondNoContentJson;
use App\Http\Responses\RespondServerErrorJson;
use App\Repositories\Interfaces\TransferRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class DeleteOperation extends AbstractOperation
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
            Gate::authorize('delete', Transfer::class);
            $id = (int) request()->route('transfer');
            DB::transaction(function () use ($id, $authUser) {
                $transfer = $this->transferRepository->getById($id);
                $this->transferRepository->delete($transfer);
            });
            return $this->runResponse(new RespondNoContentJson('success'));
        } catch (ModelNotFoundException $e) {
            return $this->runResponse(new RespondBadRequestJson('Nie znaleziono transfer'));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd usuwania transfer'));
        }
    }
}
