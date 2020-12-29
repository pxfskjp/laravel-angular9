<?php

namespace App\Domains\User\Auth;

use App\Data\Models\User;
use App\Data\Models\User\Token;
use App\Domains\AbstractOperation;
use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\User\AuthRepositoryInterface;
use App\Repositories\Interfaces\User\TokenRepositoryInterface;
use Illuminate\Http\JsonResponse;

final class LoginOperation extends AbstractOperation
{

    /**
     *
     * @var AuthRepositoryInterface $authRepository
     */
    private AuthRepositoryInterface $authRepository;

    /**
     *
     * @var TokenRepositoryInterface $tokenRepository
     */
    private TokenRepositoryInterface $tokenRepository;

    /**
     *
     * @param AuthRepositoryInterface $authRepository
     * @param TokenRepositoryInterface $tokenRepository
     */
    public function __construct(AuthRepositoryInterface $authRepository, TokenRepositoryInterface $tokenRepository)
    {
        $this->authRepository = $authRepository;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Domains\OperationInterface::handle()
     */
    public function handle(?User $authUser): JsonResponse
    {
        $loginData = $this->requestData([
            'email',
            'password'
        ]);
        $secret = Token::buildNewSecret();
        $accessToken = $this->authRepository->authAttempt($loginData, $secret, Token::getAccessTtl());
        if (empty($accessToken)) {
           return $this->runResponse(new RespondForbiddenJson());
        }
        $user = $this->authRepository->getAuthUser();
        $this->tokenRepository->saveToken(
            [
                'user_id' => $user->id,
                'secret' => $secret,
                'type' => Token::getTypeRefresh()
            ]
        );
        return $this->runResponse(new RespondSuccessJson(
            'success',
            [
                'accessToken' => $accessToken,
                'refreshToken' => $this->authRepository->createJwtToken($secret, Token::getRefreshTtl()),
                'email' => $user->email
            ]
        ));
    }
}
