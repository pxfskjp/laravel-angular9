<?php

namespace App\Repositories\User;

use App\Data\Models\User;
use App\Exceptions\BadRequestException;
use App\Repositories\Interfaces\User\AuthRepositoryInterface;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Contracts\Providers\JWT as JWTProvider;

final class AuthRepository implements AuthRepositoryInterface
{

    /**
     *
     * @var JWTAuth $auth
     */
    private JWTAuth $auth;

    /**
     *
     * @var JWTProvider $provider
     */
    private JWTProvider $provider;

    /**
     *
     * @param JWTAuth $auth
     * @param JWTProvider $provider
     */
    public function __construct(JWTAuth $auth, JWTProvider $provider)
    {
        $this->auth = $auth;
        $this->provider = $provider;
    }
    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\User\AuthRepositoryInterface::authAttempt()
     */
    public function authAttempt(array $credentials, string $secret, int $ttl): ?string
    {
        $token = null;
        if ($this->auth->attempt($credentials)) {
            $token = $this->createJwtToken($secret, $ttl);
            $this->auth->setToken($token);
        }
        return $token;
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\User\AuthRepositoryInterface::attemptFromUser()
     */
    public function attemptFromUser(User $user, string $secret, int $ttl): string
    {
        if ($this->auth->fromUser($user)) {
            $token = $this->createJwtToken($secret, $ttl);
            $this->auth->setToken($token);
            return $token;
        }
        throw new TokenInvalidException();
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\User\AuthRepositoryInterface::getAuthUser()
     */
    public function getAuthUser(): User
    {
        return $this->auth->user();
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\User\AuthRepositoryInterface::createJwtToken()
     */
    public function createJwtToken(string $secret, int $ttl): string
    {
        $payload = $this->auth
            ->factory()
            ->customClaims(
                [
                    'jti' => $secret
                ]
            )
            ->setTTL($ttl)
            ->make();
        return $this->auth->encode($payload);
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\User\AuthRepositoryInterface::getUserIdFromRefreshToken()
     */
    public function getUserIdFromRefreshToken(string $refreshToken): int
    {
        $payload = $this->provider->decode($refreshToken);
        if (empty($payload['sub'])) {
            throw new BadRequestException();
        }
        return $payload['sub'];
    }
}
