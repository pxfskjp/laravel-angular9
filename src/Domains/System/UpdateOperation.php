<?php

namespace App\Domains\System;

use App\Data\Models\System;
use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Domains\System\Validators\StoreValidator;
use App\Http\Responses\RespondBadRequestJson;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\SystemRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class UpdateOperation extends AbstractOperation
{

    private SystemRepositoryInterface $systemRepository;

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
            Gate::authorize('update', System::class);
            $input = $this->requestData(
                [
                    'name',
                ],
                true
            );
            if ($response = $this->validateWithResponse(StoreValidator::class, $input)) {
                return $response;
            }
            $system = DB::transaction(function () use ($input) {
                $system = $this->systemRepository->getById($input['id']);
                $this->systemRepository->update($system, $input);
                return $system;
            });
            return $this->runResponse(new RespondSuccessJson('success', $system));
        } catch (ModelNotFoundException $e) {
            return $this->runResponse(new RespondBadRequestJson('Nie znaleziono systemu'));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd dodawania systemu'));
        }
    }
}
