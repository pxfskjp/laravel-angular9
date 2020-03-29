<?php

namespace App\Domains\User;

use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Domains\User\Validators\UpdateValidator;
use App\Http\Responses\RespondBadRequestJson;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

final class UpdateOperation extends AbstractOperation
{

    /**
     *
     * @var \App\Data\Models${entity.name} $user
     */
    private $user;

    /**
     *
     * @var UserRepositoryInterface $userRepository
     */
    private $userRepository;

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
            Gate::authorize('update', User::class);
            $input = $this->requestData(
                [
                    'firstname',
                    'lastname',
                    'email',
                ],
                true
            );
            if ($response = $this->validateWithResponse(UpdateValidator::class, $input)) {
                return $response;
            }
            DB::transaction(function () use ($input) {
                $this->user = $this->userRepository->getById($input['id']);
                $this->userRepository->update($this->user, $input);
            });
            return $this->runResponse(new RespondSuccessJson('success', $this->user->toArray()));
        } catch (ModelNotFoundException $e) {
            return $this->runResponse(new RespondBadRequestJson('Nie znaleziono użytkownika'));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd dodawania użytkownika'));
        }
    }
}
