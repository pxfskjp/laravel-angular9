<?php

namespace App\Domains\Hardware;

use App\Data\Models\Hardware;
use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Http\Responses\RespondBadRequestJson;
use App\Http\Responses\RespondNoContentJson;
use App\Http\Responses\RespondServerErrorJson;
use App\Repositories\Interfaces\HardwareRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class DeleteOperation extends AbstractOperation
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
            Gate::authorize('delete', Hardware::class);
            $id = (int) request()->route('id');
            DB::transaction(function () use ($id) {
                $hardware = $this->hardwareRepository->getById($id);
                $this->hardwareRepository->delete($hardware);
            });
            return $this->runResponse(new RespondNoContentJson('success'));
        } catch (ModelNotFoundException $e) {
            return $this->runResponse(new RespondBadRequestJson('Nie znaleziono komputera'));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd usuwania komputera'));
        }
    }
}
