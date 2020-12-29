<?php

namespace App\Domains\Hardware;

use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\HardwareRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

final class ListOperation extends AbstractOperation
{

    /**
     *
     * @var HardwareRepositoryInterface $hardwareRepository
     */
    private HardwareRepositoryInterface $hardwareRepository;

    /**
     *
     * @param HardwareRepositoryInterface $hardwareRepository
     */
    public function __construct(HardwareRepositoryInterface $hardwareRepository)
    {
        $this->hardwareRepository = $hardwareRepository;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Domains\OperationInterface::handle()
     */
    public function handle(?User $authUser): JsonResponse
    {
        try {
            $hardwares = $this->hardwareRepository->list();
            return $this->runResponse(new RespondSuccessJson('success', $hardwares));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd pobierania listy'));
        }
    }
}
