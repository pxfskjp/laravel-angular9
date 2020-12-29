<?php

namespace App\Providers\JWT;

use App\Data\Models\User\Token;
use App\Repositories\Interfaces\User\TokenRepositoryInterface;
use App\Providers\JWT\Signer\Signer;
use Carbon\Carbon;
use Tymon\JWTAuth\Contracts\Providers\JWT;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

final class JWTProvider implements JWT
{

    private const TOKEN_TYPE = 'JWT-JS';

    /**
     *
     * @var string $jwtHead
     */
    private $jwtHead;

    /**
     *
     * @var string $jwtBody
     */
    private $jwtBody;

    /**
     *
     * @var string $jwtSignature
     */
    private $jwtSignature;

    /**
     *
     * @var Signer $signer
     */
    private $signer;

    /**
     *
     * @var TokenRepositoryInterface $tokenRepository
     */
    private $tokenRepository;

    /**
     *
     * @var string $key
     */
    private $key;

    /**
     *
     * @param Token $jwtToken
     * @param Signer $signer
     * @param TokenRepositoryInterface $tokenRepository
     */
    public function __construct(Token $jwtToken, Signer $signer, TokenRepositoryInterface $tokenRepository)
    {
        $this->key = $jwtToken->getJwtTokenKey();
        $this->signer = $signer;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Tymon\JWTAuth\Contracts\Providers\JWT::decode()
     */
    public function decode($jwt)
    {
        list($this->jwtHead, $this->jwtBody, $this->jwtSignature) = $this->explodeSegments($jwt);
        $this->parseHeader();
        $this->parseSignature();
        return $this->parsePayload();
    }

    /**
     *
     * {@inheritDoc}
     * @see \Tymon\JWTAuth\Contracts\Providers\JWT::encode()
     */
    public function encode(array $payload)
    {
        $segments = [
            $this->base64Encode(\json_encode(['type' => static::TOKEN_TYPE])),
            $this->base64Encode(\json_encode($payload))
        ];
        $signature = $this->createSignature(\implode('.', $segments));
        $segments[] = $this->base64Encode($signature);
        return \implode('.', $segments);
    }

    /**
     *
     * @param string $jwt
     * @throws TokenInvalidException
     * @return array
     */
    private function explodeSegments(string $jwt): array
    {
        $tks = \explode('.', $jwt);
        if (\count($tks) != 3) {
            throw new TokenInvalidException('Wrong number of segments');
        }
        return $tks;
    }

    /**
     *
     * @throws TokenInvalidException
     */
    private function parseHeader(): void
    {
        $header = \json_decode($this->base64Decode($this->jwtHead));
        if (empty($header)) {
            throw new TokenInvalidException('Invalid header encoding');
        }
        if (empty($header->type)) {
            throw new TokenInvalidException('Empty header type');
        }
        if ($header->type !== static::TOKEN_TYPE) {
            throw new TokenInvalidException('Bad header type');
        }
    }

    /**
     *
     * @throws TokenInvalidException
     * @throws TokenExpiredException
     * @return array
     */
    private function parsePayload(): array
    {
        $timestamp = Carbon::now()->timestamp;
        $payload = \json_decode($this->base64Decode($this->jwtBody), true);
        if (null === $payload) {
            throw new TokenInvalidException('Invalid claims encoding');
        }
        if (! $this->tokenRepository->checkBySecret($payload['jti'])) {
            throw new TokenInvalidException('Token not in whitelist');
        }
        if (isset($payload['nbf']) && $payload['nbf'] > $timestamp) {
            throw new TokenInvalidException('Cannot handle token prior to '
                . Carbon::parse($payload['nbf'])->format('Y-m-d H:i:s'));
        }

        if (isset($payload['iat']) && $payload['iat'] > $timestamp) {
            throw new TokenInvalidException('Cannot handle token prior to '
                . Carbon::parse($payload['iat'])->format('Y-m-d H:i:s'));
        }
        if (isset($payload['exp']) && $timestamp >= $payload['exp']) {
            throw new TokenExpiredException('Expired token');
        }
        return $payload;
    }

    /**
     *
     * @throws TokenInvalidException
     */
    private function parseSignature(): void
    {
        $sig = $this->base64Decode($this->jwtSignature);
        if (false === $sig) {
            throw new TokenInvalidException('Invalid signature encoding');
        }
        if (! $this->signer->verifyHash($sig, "$this->jwtHead.$this->jwtBody", $this->key)) {
            throw new TokenInvalidException('Signature verification failed');
        }
    }

    /**
     *
     * @param mixed $data
     * @return mixed
     */
    private function base64Encode($data)
    {
        return \str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }

    /**
     *
     * @param string $data
     * @return mixed
     */
    private function base64Decode(string $data)
    {
        if ($remainder = \strlen($data) % 4) {
            $data .= \str_repeat('=', 4 - $remainder);
        }

        return \base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     *
     * @param string $content
     * @return string
     */
    private function createSignature(string $content): string
    {
        return $this->signer->createHash($content, $this->key);
    }
}
