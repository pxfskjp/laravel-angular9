<?php

namespace Tests\Feature\Hardware;

use App\Data\Models\Hardware;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;

/**
 * @group api
 * @group HardwareController
 */
class ListTest extends ApiTestCase
{
    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'hardware.index';

    /**
     * @test
     */
    public function listSuccess(): void
    {
        $token = $this->setAdminAndJwtToken();
        factory(Hardware::class, 2)->create();
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader($token));
        $response->assertOk();
        $response->assertJsonStructure([
            'message',
            'result' => [
                '*' => [
                    'id',
                    'name',
                    'serial_number',
                    'production_year',
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function listFailureNotLoggedinUser(): void
    {
        factory(Hardware::class)->create();
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader(''));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function listFailureBadToken(): void
    {
        $token = $this->setAdminAndJwtToken();
        factory(Hardware::class)->create();
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader($token . 'a'));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function deleteFailureTokenExpired(): void
    {
        $token = $this->setAdminAndJwtToken();
        factory(Hardware::class)->create();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
