<?php

namespace Tests\Feature\System;

use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondSuccessJson;
use App\Http\Responses\RespondUnauthorizedJson;
use App\Http\Responses\RespondValidationErrorsJson;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
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
        $response = $this->postRequest($this->apiRoute, [], [
            'name' => $this->faker->text(100),
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondSuccessJson())->getResponseHeader());
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
        $response = $this->postRequest($this->apiRoute, [], [
            'name' => $this->faker->text(100),
        ], $this->getBearerHeader(''));
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureNoName(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'name' => '',
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureNameTooLong(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'name' => $this->faker->lexify(\str_repeat('?', 101)),
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureBadToken(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'name' => $this->faker->text(100),
        ], $this->getBearerHeader($token . 'a'));
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
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
        $response = $this->postRequest($this->apiRoute, [], [
            'name' => $this->faker->text(100),
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
