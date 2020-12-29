<?php

namespace App\Domains\User\Auth;

use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Http\Responses\RespondSuccessJson;
use App\Repositories\Interfaces\User\TokenRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\JWTAuth;

final class LogoutOperation extends AbstractOperation
{

    /**
     *
     * @var JwtAuth $auth
     */
    private JwtAuth $auth;

    /**
     *
     * @var TokenRepositoryInterface $tokenRepository
     */
    private TokenRepositoryInterface $tokenRepository;

    /**
     *
     * @param JwtAuth $auth
     */
    public function __construct(JwtAuth $auth, TokenRepositoryInterface $tokenRepository)
    {
        $this->auth = $auth;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Domains\OperationInterface::handle()
     */
    public function handle(?User $authUser): JsonResponse
    {
        $payload = $this->auth->getPayload();
        $secret = $payload['jti'];
        $this->tokenRepository->destroyBySecret($secret);
        return $this->runResponse(new RespondSuccessJson('success logout'));
    }
}
