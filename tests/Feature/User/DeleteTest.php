<?php

namespace Tests\Feature\User;

use App\Data\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;

/**
 *
 * @group api
 * @group UserController
 */
class DeleteTest extends ApiTestCase
{
    use WithFaker;

    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'user.destroy';

    /**
     *
     * @test
     */
    public function deleteSuccess(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $user->id
        ], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     *
     * @test
     */
    public function deleteFailureNoUser(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $user->id + 1
        ], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     *
     * @test
     */
    public function deleteFailureNotLoggedinUser(): void
    {
        $user = factory(User::class)->create();
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $user->id
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
        $user = factory(User::class)->create();
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $user->id
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
        $user = factory(User::class)->create();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $user->id
        ], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
