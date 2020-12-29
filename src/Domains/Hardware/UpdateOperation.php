<?php

namespace App\Domains\Hardware;

use App\Data\Models\Hardware;
use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Domains\Hardware\Validators\StoreValidator;
use App\Http\Responses\RespondBadRequestJson;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\HardwareRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class UpdateOperation extends AbstractOperation
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
            Gate::authorize('update', Hardware::class);
            $input = $this->requestData(
                [
                    'system_id',
                    'name',
                    'serial_number',
                    'production_year',
                ],
                true
            );
            if ($response = $this->validateWithResponse(StoreValidator::class, $input)) {
                return $response;
            }
            $hardware = DB::transaction(function () use ($input) {
                $hardware = $this->hardwareRepository->getById($input['id']);
                $this->hardwareRepository->update($hardware, $input);
                return $hardware;
            });
            return $this->runResponse(new RespondSuccessJson('success', $hardware));
        } catch (ModelNotFoundException $e) {
            return $this->runResponse(new RespondBadRequestJson('Nie znaleziono hardware'));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd dodawania hardware'));
        }
    }
}
