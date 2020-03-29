<?php

namespace App\Providers\JWT\Signer;

final class Sha256 implements Signer
{
    private const HMAC = 'sha256';

    /**
     *
     * {@inheritDoc}
     * @see \App\Providers\JWT\Signer\Signer::createHash()
     */
    public function createHash(string $content, string $key): string
    {
        return hash_hmac(static::HMAC, $content, $key, true);
    }

    /**
     *
     * {@inheritDoc}
     * @see \App\Providers\JWT\Signer\Signer::verifyHash()
     */
    public function verifyHash(string $expected, string $content, string $key): bool
    {
        if (!is_string($expected)) {
            return false;
        }
        return \hash_equals($expected, $this->createHash($content, $key));
    }
}
