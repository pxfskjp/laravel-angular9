<?php

namespace App\Domains\User;

use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Domains\User\Transformers\UserTransformer;
use App\Http\Responses\RespondServerErrorJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

final class ListOperation extends AbstractOperation
{

    /**
     *
     * @var UserRepositoryInterface $userRepository
     */
    private $userRepository;

    /**
     *
     * @var UserTransformer $transformer
     */
    private $transformer;

    /**
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, UserTransformer $transformer)
    {
        $this->userRepository = $userRepository;
        $this->transformer = $transformer;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Domains\OperationInterface::handle()
     */
    public function handle(?User $uthUser): JsonResponse
    {
        try {
            $users = $this->userRepository->list();
            return $this->runResponse(new RespondSuccessJson('success', $this->transformer->transform($users)));
        } catch (QueryException $e) {
            return $this->runResponse(new RespondServerErrorJson('Błąd pobierania listy użytkowników'));
        }
    }
}
