<?php

namespace Tests\Feature\Hardware;

use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondSuccessJson;
use App\Http\Responses\RespondUnauthorizedJson;
use App\Http\Responses\RespondValidationErrorsJson;
use App\Repositories\Interfaces\HardwareRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ApiTestCase;
use Mockery;
use App\Data\Models\Hardware;

/**
 *
 * @group api
 * @group HardwareController
 */
class StoreTest extends ApiTestCase
{
    use WithFaker;

    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'hardware.store';

    /**
     *
     * @test
     */
    public function storeSuccess(): void
    {
        $user = $this->setLoggedUser('qwerty123');
        $token = $this->setJwtToken($user,'qwerty123');
        $repository = Mockery::mock(HardwareRepositoryInterface::class);
        $repository->shouldReceive('store')
            ->with([
                'name' => '123',
                'serial_number' => '234',
                'production_year' => '1995',
                'system_id' => ''
            ])
            ->once()
            ->andReturn(new Hardware([
                'name' => '123',
                'serial_number' => '234',
                'production_year' => '1995',
                'system_id' => ''
            ]));
        $this->app->instance(HardwareRepositoryInterface::class, $repository);
        $response = $this->postRequest($this->apiRoute, [], [
            'name' => '123',
            'serial_number' => '234',
            'production_year' => '1995',
            'system_id' => ''
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondSuccessJson())->getResponseHeader());
        $response->assertJsonStructure([
            'message',
            'result' => [
                'id',
                'name',
                'serial_number',
                'production_year',
            ]
        ]);
    }

    /**
     *
     * @test
     */
    public function storeFailureNotLoggedinUser(): void
    {
        $response = $this->postRequest($this->apiRoute, [], [
            'name' => $this->faker->company,
            'serial_number' => $this->faker->uuid,
            'production_year' => $this->faker->year,
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
            'serial_number' => $this->faker->uuid,
            'production_year' => $this->faker->year,
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
            'serial_number' => $this->faker->uuid,
            'production_year' => $this->faker->year,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureNoSerialNumber(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'name' => $this->faker->company,
            'production_year' => $this->faker->year,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureSerialNumberTooLong(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'name' => $this->faker->company,
            'serial_number' => $this->faker->lexify(\str_repeat('?', 101)),
            'production_year' => $this->faker->year,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureNoProductionYear(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'name' => $this->faker->company,
            'serial_number' => $this->faker->uuid,
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
            'name' => $this->faker->company,
            'serial_number' => $this->faker->uuid,
            'production_year' => $this->faker->year,
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
            'name' => $this->faker->company,
            'serial_number' => $this->faker->uuid,
            'production_year' => $this->faker->year,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
