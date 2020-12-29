<?php

namespace App\Repositories\Interfaces\User;

use App\Data\Models\User;

interface AuthRepositoryInterface
{

    /**
     *
     * @param array $credentials
     * @param string $secret
     * @param int $ttl
     * @return string|null
     */
    public function authAttempt(array $credentials, string $secret, int $ttl): ?string;

    /**
     *
     * @param User $user
     * @param string $secret
     * @param int $ttl
     * @return string
     */
    public function attemptFromUser(User $user, string $secret, int $ttl): string;

    /**
     *
     * @return User
     */
    public function getAuthUser(): User;

    /**
     *
     * @param string $secret
     * @param int $ttl
     * @return string
     */
    public function createJwtToken(string $secret, int $ttl): string;

    /**
     *
     * @param string $refreshToken
     * @return int
     */
    public function getUserIdFromRefreshToken(string $refreshToken): int;
}
