<?php

namespace App\Repositories\Interfaces\User;

use App\Data\Models\User\Token;

interface TokenRepositoryInterface
{

    /**
     *
     * @param int $userId
     * @param int $type
     * @return Token
     */
    public function storeWithType(int $userId, int $type): Token;

    /**
     *
     * @param string $token
     */
    public function destroyBySecret(string $token): void;

    /**
     *
     * @param string $token
     * @return bool
     */
    public function checkBySecret(string $token): bool;

    /**
     *
     * @param array $data
     */
    public function saveToken(array $data): void;

    /**
     *
     * @param int $userId
     */
    public function deleteRefreshByUserId(int $userId): void;
}
