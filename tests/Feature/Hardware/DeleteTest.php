<?php

namespace Tests\Feature\Hardware;

use App\Data\Models\Hardware;
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
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $hardware->id
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondNoContentJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function deleteFailureNoHardware(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $hardware->id + 1
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondBadRequestJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function deleteFailureNotLoggedinUser(): void
    {
        $hardware = factory(Hardware::class)->create();
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $hardware->id
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
        $hardware = factory(Hardware::class)->create();
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $hardware->id
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
        $hardware = factory(Hardware::class)->create();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->deleteRequest($this->apiRoute, [
            'id' => $hardware->id
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
