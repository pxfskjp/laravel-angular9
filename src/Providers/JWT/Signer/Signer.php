<?php

namespace App\Providers\JWT\Signer;

interface Signer
{

    /**
     *
     * @param string $content
     * @param string $key
     * @return string
     */
    public function createHash(string $content, string $key): string;

    /**
     *
     * @param string $expected
     * @param string $content
     * @param string $key
     * @return bool
     */
    public function verifyHash(string $expected, string $content, string $key): bool;
}
