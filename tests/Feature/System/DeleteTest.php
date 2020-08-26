<?php

namespace Tests\Feature\System;

use App\Data\Models\System;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;

/**
 *
 * @group api
 * @group SystemController
 */
class DeleteTest extends ApiTestCase
{
    use WithFaker;

    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'system.destroy';

    /**
     *
     * @test
     */
    public function deleteSuccess(): void
    {
        $token = $this->setAdminAndJwtToken();
        $system = factory(System::class)->create();
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $system->id
        ], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     *
     * @test
     */
    public function deleteFailureNoSystem(): void
    {
        $token = $this->setAdminAndJwtToken();
        $system = factory(System::class)->create();
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $system->id + 1
        ], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     *
     * @test
     */
    public function deleteFailureNotLoggedinUser(): void
    {
        $system = factory(System::class)->create();
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $system->id
        ], $this->getBearerHeader(''));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     * @test
     */
    public function deleteFailureBadToken(): void
    {
        $token = $this->setAdminAndJwtToken();
        $system = factory(System::class)->create();
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $system->id
        ], $this->getBearerHeader($token . 'a'));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     * @test
     */
    public function deleteFailureTokenExpired(): void
    {
        $token = $this->setAdminAndJwtToken();
        $system = factory(System::class)->create();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $system->id
        ], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
