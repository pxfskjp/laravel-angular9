<?php

namespace App\Domains\User\Auth;

use App\Data\Models\User;
use App\Data\Models\User\Token;
use App\Domains\AbstractOperation;
use App\Exceptions\BadRequestException;
use App\Http\Responses\RespondBadRequestJson;
use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\User\AuthRepositoryInterface;
use App\Repositories\Interfaces\User\TokenRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

final class RefreshTokenOperation extends AbstractOperation
{

    private AuthRepositoryInterface $authRepository;

    private UserRepositoryInterface $userRepository;

    private TokenRepositoryInterface $tokenRepository;

    private User $user;

    /**
     *
     * @param AuthRepositoryInterface $authRepository
     * @param UserRepositoryInterface $userRepository
     * @param TokenRepositoryInterface $tokenRepository
     */
    public function __construct(
        AuthRepositoryInterface $authRepository,
        UserRepositoryInterface $userRepository,
        TokenRepositoryInterface $tokenRepository)
    {
        $this->authRepository = $authRepository;
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Domains\OperationInterface::handle()
     */
    public function handle(?User $authUser): JsonResponse
    {
        $refreshToken = request()->input('refreshToken');
        try {
            $secret = Token::buildNewSecret();
            DB::transaction(function () use ($secret, $refreshToken) {
                $userId = $this->authRepository->getUserIdFromRefreshToken($refreshToken);
                $this->user = $this->userRepository->getById($userId);
                $this->tokenRepository->deleteRefreshByUserId($userId);
                $this->tokenRepository->saveToken(
                    [
                        'user_id' => $userId,
                        'secret' => $secret,
                        'type' => Token::getTypeRefresh()
                    ]
                );
            });
            return $this->runResponse(new RespondSuccessJson(
                'success',
                [
                    'accessToken' => $this->authRepository->attemptFromUser($this->user, $secret, Token::getAccessTtl()),
                    'refreshToken' => $this->authRepository->createJwtToken($secret, Token::getRefreshTtl()),
                    'email' => $this->user->email
                ]
                ));
        } catch (BadRequestException | ModelNotFoundException $e) {
            return $this->runResponse(new RespondBadRequestJson());
        } catch (TokenExpiredException | TokenInvalidException $e) {
            return $this->runResponse(new RespondForbiddenJson());
        }
    }
}
