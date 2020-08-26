<?php

namespace App\Domains\Hardware\System;

use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\SystemRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

final class ListOperation extends AbstractOperation
{

    private SystemRepositoryInterface $hardwareSystemRepository;

    /**
     *
     * @param SystemRepositoryInterface $hardwareSystemRepository
     */
    public function __construct(SystemRepositoryInterface $hardwareSystemRepository)
    {
        $this->hardwareSystemRepository = $hardwareSystemRepository;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Domains\OperationInterface::handle()
     */
    public function handle(?User $authUser): JsonResponse
    {
        try {
            $hardwareSystems = $this->hardwareSystemRepository->list();
            return $this->runResponse(new RespondSuccessJson('success', $hardwareSystems));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd pobierania listy'));
        }
    }
}
