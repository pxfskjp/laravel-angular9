<?php
namespace Tests\Feature\Hardware;

use App\Data\Models\Hardware;
use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondSuccessJson;
use App\Http\Responses\RespondUnauthorizedJson;
use Carbon\Carbon;
use Tests\ApiTestCase;

class ListTest extends ApiTestCase
{
    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'hardware.index';

    /**
     *
     * @test
     *
     * @group api
     * @group HardwareController
     *
     */
    public function listSuccess(): void
    {
        $token = $this->setAdminAndJwtToken();
        factory(Hardware::class)->create();
        factory(Hardware::class)->create();
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader($token));
        $response->assertStatus((new RespondSuccessJson())->getResponseHeader());
        $response->assertJsonStructure([
            'message',
            'result' => [
                '*' => [
                    'id',
                    'name',
                    'serial_number',
                    'production_year',
                ]
            ]
        ]);
    }

    /**
     *
     * @test
     *
     * @group api
     * @group HardwareController
     *
     */
    public function listFailureNotLoggedinUser(): void
    {
        factory(Hardware::class)->create();
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader(''));
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
    }

    /**
     *
     * @test
     *
     * @group api
     * @group HardwareController
     *
     */
    public function listFailureBadToken(): void
    {
        $token = $this->setAdminAndJwtToken();
        factory(Hardware::class)->create();
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader($token . 'a'));
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
    }

    /**
     *
     * @test
     *
     * @group api
     * @group HardwareController
     *
     */
    public function deleteFailureTokenExpired(): void
    {
        $token = $this->setAdminAndJwtToken();
        factory(Hardware::class)->create();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->getRequest($this->apiRoute, [], $this->getBearerHeader($token));
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
