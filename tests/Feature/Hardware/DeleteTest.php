<?php

namespace Tests\Feature\Hardware;

use App\Data\Models\Hardware;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;

/**
 *
 * @group api
 * @group HardwareController
 */
class DeleteTest extends ApiTestCase
{
    use WithFaker;

    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'hardware.destroy';

    /**
     *
     * @test
     */
    public function deleteSuccess(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $response = $this->deleteRequest($this->apiRoute, ['id' => $hardware->id], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     *
     * @test
     */
    public function deleteFailureNoHardware(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $response = $this->deleteRequest($this->apiRoute, ['id' => $hardware->id + 1], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     *
     * @test
     */
    public function deleteFailureNotLoggedinUser(): void
    {
        $hardware = factory(Hardware::class)->create();
        $response = $this->deleteRequest($this->apiRoute, ['id' => $hardware->id], $this->getBearerHeader(''));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     * @test
     */
    public function deleteFailureBadToken(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $response = $this->deleteRequest(
            $this->apiRoute,
            [
                'id' => $hardware->id
            ],
            $this->getBearerHeader($token . 'a')
        );
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     * @test
     */
    public function deleteFailureTokenExpired(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->deleteRequest($this->apiRoute, ['id' => $hardware->id], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
