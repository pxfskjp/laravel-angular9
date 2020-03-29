<?php

namespace Tests\Feature\System;

use App\Data\Models\System;
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
class UpdateTest extends ApiTestCase
{
    use WithFaker;

    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'system.update';

    /**
     *
     * @test
     */
    public function updateSuccess(): void
    {
        $token = $this->setAdminAndJwtToken();
        $system = factory(System::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $system->id
        ], [
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
    public function updateFailureNotLoggedinUser(): void
    {
        $system = factory(System::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $system->id
        ], [
            'name' => '',
        ], $this->getBearerHeader(''));
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function updateFailureNoName(): void
    {
        $token = $this->setAdminAndJwtToken();
        $system = factory(System::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $system->id
        ], [
            'name' => '',
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function updateFailureNameTooLong(): void
    {
        $token = $this->setAdminAndJwtToken();
        $system = factory(System::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $system->id
        ], [
            'name' => $this->faker->lexify(\str_repeat('?', 101)),
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function updateFailureBadToken(): void
    {
        $token = $this->setAdminAndJwtToken();
        $system = factory(System::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $system->id
        ], [
            'name' => '',
        ], $this->getBearerHeader($token . 'a'));
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function updateFailureTokenExpired(): void
    {
        $token = $this->setAdminAndJwtToken();
        $system = factory(System::class)->create();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->putRequest($this->apiRoute, [
            'id' => $system->id
        ], [
            'name' => '',
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
