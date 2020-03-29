<?php

namespace Tests\Feature\System;

use App\Data\Models\System;
use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondSuccessJson;
use App\Http\Responses\RespondUnauthorizedJson;
use Carbon\Carbon;
use Tests\ApiTestCase;

/**
 *
 * @group api
 * @group SystemController
 */
class ListTest extends ApiTestCase
{
    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'system.index';

    /**
     *
     * @test
     */
    public function listSuccess(): void
    {
        $token = $this->setAdminAndJwtToken();
        factory(System::class)->create();
        factory(System::class)->create();
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader($token));
        $response->assertStatus((new RespondSuccessJson())->getResponseHeader());
        $response->assertJsonStructure([
            'message',
            'result' => [
                '*' => [
                    'id',
                    'name',
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
        factory(System::class)->create();
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader(''));
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function listFailureBadToken(): void
    {
        $token = $this->setAdminAndJwtToken();
        factory(System::class)->create();
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader($token . 'a'));
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function deleteFailureTokenExpired(): void
    {
        $token = $this->setAdminAndJwtToken();
        factory(System::class)->create();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader($token));
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
