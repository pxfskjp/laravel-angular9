<?php

namespace App\Repositories\User;

use App\Data\Models\User\Token;
use App\Repositories\Interfaces\User\TokenRepositoryInterface;
use Illuminate\Support\Str;

final class TokenRepository implements TokenRepositoryInterface
{

    /**
     *
     * {@inheritdoc}
     * @see \App\Repositories\Interfaces\User\TokenRepositoryInterface::storeRegister()
     */
    public function storeWithType(int $userId, int $type): Token
    {
        return Token::create([
            'user_id' => $userId,
            'secret' => Str::uuid()->toString(),
            'type' => $type
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\User\TokenRepositoryInterface::destroyBySecret()
     */
    public function destroyBySecret(string $token): void
    {
        Token::whereSecret($token)
            ->delete();
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\User\TokenRepositoryInterface::checkBySecret()
     */
    public function checkBySecret(string $token): bool
    {
        return Token::whereSecret($token)
            ->exists();
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\User\TokenRepositoryInterface::saveToken()
     */
    public function saveToken(array $data): void
    {
        Token::create($data);
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Repositories\Interfaces\User\TokenRepositoryInterface::deleteRefreshByUserId()
     */
    public function deleteRefreshByUserId(int $userId): void
    {
        Token::whereUserIdAndType($userId, Token::getTypeRefresh())
            ->delete();
    }
}
