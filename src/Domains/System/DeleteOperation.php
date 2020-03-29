<?php

namespace App\Domains\System;

use App\Data\Models\System;
use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Http\Responses\RespondBadRequestJson;
use App\Http\Responses\RespondNoContentJson;
use App\Http\Responses\RespondServerErrorJson;
use App\Repositories\Interfaces\SystemRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class DeleteOperation extends AbstractOperation
{

    /**
     *
     * @var SystemRepositoryInterface $systemRepository
     */
    private $systemRepository;

    /**
     *
     * @param SystemRepositoryInterface $systemRepository
     */
    public function __construct(SystemRepositoryInterface $systemRepository)
    {
        $this->systemRepository = $systemRepository;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Domains\OperationInterface::handle()
     */
    public function handle(?User $authUser): JsonResponse
    {
        try {
            Gate::authorize('delete', System::class);
            $id = (int) request()->route('id');
            DB::transaction(function () use ($id) {
                $system = $this->systemRepository->getById($id);
                $this->systemRepository->delete($system);
            });
            return $this->runResponse(new RespondNoContentJson('success'));
        } catch (ModelNotFoundException $e) {
            return $this->runResponse(new RespondBadRequestJson('Nie znaleziono systemu'));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd usuwania systemu'));
        }
    }
}
