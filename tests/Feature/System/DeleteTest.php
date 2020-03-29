<?php

namespace Tests\Feature\System;

use App\Data\Models\System;
use App\Http\Responses\RespondBadRequestJson;
use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondNoContentJson;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ApiTestCase;
use App\Http\Responses\RespondUnauthorizedJson;

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
        $response->assertStatus((new RespondNoContentJson())->getResponseHeader());
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
        $response->assertStatus((new RespondBadRequestJson())->getResponseHeader());
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
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
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
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
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
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
