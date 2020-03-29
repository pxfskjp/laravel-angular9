<?php

namespace Tests\Feature\User;

use App\Data\Models\User;
use App\Http\Responses\RespondBadRequestJson;
use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondNoContentJson;
use App\Http\Responses\RespondUnauthorizedJson;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
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
        $response->assertStatus((new RespondNoContentJson())->getResponseHeader());
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
        $response->assertStatus((new RespondBadRequestJson())->getResponseHeader());
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
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
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
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
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
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
