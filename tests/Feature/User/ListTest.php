<?php

namespace Tests\Feature\User;

use App\Data\Models\User;
use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondSuccessJson;
use App\Http\Responses\RespondUnauthorizedJson;
use Carbon\Carbon;
use Tests\ApiTestCase;

/**
 *
 * @group api
 * @group UserController
 */
class ListTest extends ApiTestCase
{
    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'user.index';

    /**
     *
     * @test
     */
    public function listSuccess(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader($token));
        $response->assertStatus((new RespondSuccessJson())->getResponseHeader());
        $response->assertJsonStructure([
            'message',
            'result' => [
                '*' => [
                    'id',
                    'firstname',
                    'lastname',
                    'email',
                ]
            ]
        ]);
    }

    /**
     *
     * @test
     */
    public function listFailureNotLoggedinUser(): void
    {
        factory(User::class)->create();
        $response = $this->getRequest($this->apiRoute, [
        ], $this->getBearerHeader(''));
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function listFailureBadToken(): void
    {
        $token = $this->setAdminAndJwtToken();
        factory(User::class)->create();
        $response = $this->getRequest($this->apiRoute, [
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
        factory(User::class)->create();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->getRequest($this->apiRoute, [
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
