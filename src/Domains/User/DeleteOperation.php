<?php

namespace App\Domains\User;

use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Http\Responses\RespondBadRequestJson;
use App\Http\Responses\RespondNoContentJson;
use App\Http\Responses\RespondServerErrorJson;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class DeleteOperation extends AbstractOperation
{

    /**
     *
     * @var UserRepositoryInterface $userRepository
     */
    private UserRepositoryInterface $userRepository;

    /**
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Domains\OperationInterface::handle()
     */
    public function handle(?User $authUser): JsonResponse
    {
        try {
            Gate::authorize('delete', User::class);
            $id = (int) request()->route('id');
            DB::transaction(function () use ($id) {
                $user = $this->userRepository->getById($id);
                $this->userRepository->delete($user);
            });
            return $this->runResponse(new RespondNoContentJson('success'));
        } catch (ModelNotFoundException $e) {
            return $this->runResponse(new RespondBadRequestJson('Nie znaleziono user'));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd usuwania user'));
        }
    }
}
