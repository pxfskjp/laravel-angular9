<?php

namespace App\Domains\System;

use App\Data\Models\System;
use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Domains\System\Validators\StoreValidator;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\SystemRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

final class StoreOperation extends AbstractOperation
{

    /**
     *
     * @var SystemRepositoryInterface $systemRepository
     */
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
            Gate::authorize('store', System::class);
            $input = $this->requestData(
                [
                    'name',
                ]
            );
            if ($response = $this->validateWithResponse(StoreValidator::class, $input)) {
                return $response;
            }
            $system = $this->systemRepository->store($input);
            return $this->runResponse(new RespondSuccessJson('success', $system));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd dodawania system'));
        }
    }
}
