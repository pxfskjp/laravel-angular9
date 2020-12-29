<?php

namespace Tests\Feature\System;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;

/**
 *
 * @group api
 * @group SystemController
 */
class StoreTest extends ApiTestCase
{
    use WithFaker;

    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'system.store';

    /**
     *
     * @test
     */
    public function storeSuccess(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest(
            $this->apiRoute,
            [],
            [
                'name' => $this->faker->text(100)
            ],
            $this->getBearerHeader($token)
        );
        $response->assertOk();
        $response->assertJsonStructure([
            'message',
            'result'
        ]);
    }

    /**
     *
     * @test
     */
    public function storeFailureNotLoggedinUser(): void
    {
        $response = $this->postRequest(
            $this->apiRoute,
            [],
            [
                'name' => $this->faker->text(100)
            ],
            $this->getBearerHeader('')
        );
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     * @test
     */
    public function storeFailureNoName(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], ['name' => ''], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     *
     * @test
     */
    public function storeFailureNameTooLong(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest(
            $this->apiRoute,
            [],
            [
                'name' => $this->faker->lexify(\str_repeat('?', 101))
            ],
            $this->getBearerHeader($token)
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     *
     * @test
     */
    public function storeFailureBadToken(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest(
            $this->apiRoute,
            [],
            [
                'name' => $this->faker->text(100)
            ],
            $this->getBearerHeader($token . 'a')
        );
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     * @test
     */
    public function storeFailureTokenExpired(): void
    {
        $token = $this->setAdminAndJwtToken();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->postRequest(
            $this->apiRoute,
            [],
            [
                'name' => $this->faker->text(100)
            ],
            $this->getBearerHeader($token)
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
