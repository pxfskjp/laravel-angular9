<?php

namespace App\Domains\User;

use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Domains\User\Validators\StoreValidator;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

final class StoreOperation extends AbstractOperation
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
            Gate::authorize('store', User::class);
            $input = $this->requestData(
                [
                    'firstname',
                    'lastname',
                    'email',
                ]
            );
            if ($response = $this->validateWithResponse(StoreValidator::class, $input)) {
                return $response;
            }
            $user = $this->userRepository->store($input);
            return $this->runResponse(new RespondSuccessJson('success', $user));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd dodawania użytkownika'));
        }
    }
}
