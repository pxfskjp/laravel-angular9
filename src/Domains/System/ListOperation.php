<?php

namespace App\Domains\System;

use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\SystemRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

final class ListOperation extends AbstractOperation
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
            $systems = $this->systemRepository->list();
            return $this->runResponse(new RespondSuccessJson('success', $systems));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd pobierania listy systemów'));
        }
    }
}
