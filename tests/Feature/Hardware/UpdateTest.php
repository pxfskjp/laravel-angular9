<?php

namespace Tests\Feature\Hardware;

use App\Data\Models\Hardware;
use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondSuccessJson;
use App\Http\Responses\RespondUnauthorizedJson;
use App\Http\Responses\RespondValidationErrorsJson;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ApiTestCase;
use App\Http\Responses\RespondBadRequestJson;

/**
 *
 * @group api
 * @group HardwareController
 */
class UpdateTest extends ApiTestCase
{
    use WithFaker;

    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'hardware.update';

    /**
     *
     * @test
     */
    public function updateSuccess(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $hardware->id
        ], [
            'name' => $this->faker->company,
            'serial_number' => $this->faker->uuid,
            'production_year' => $this->faker->year,
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
        $hardware = factory(Hardware::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $hardware->id
        ], [
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
    public function updateFailureBadHardware(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $hardware->id + 1
        ], [
            'name' => $this->faker->company,
            'serial_number' => $this->faker->uuid,
            'production_year' => $this->faker->year,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondBadRequestJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function updateFailureNoName(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $hardware->id
        ], [
            'name' => '',
            'serial_number' => $this->faker->uuid,
            'production_year' => $this->faker->year,
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
        $hardware = factory(Hardware::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $hardware->id
        ], [
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
    public function updateFailureNoSerialNumber(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $hardware->id
        ], [
            'name' => $this->faker->company,
            'serial_number' => '',
            'production_year' => $this->faker->year,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function updateFailureSerialNumberTooLong(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $hardware->id
        ], [
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
    public function updateFailureNoProductionYear(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $hardware->id
        ], [
            'name' => $this->faker->company,
            'serial_number' => $this->faker->uuid,
            'production_year' => '',
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
        $hardware = factory(Hardware::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $hardware->id
        ], [
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
    public function updateFailureTokenExpired(): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->putRequest($this->apiRoute, [
            'id' => $hardware->id
        ], [
            'name' => $this->faker->company,
            'serial_number' => $this->faker->uuid,
            'production_year' => $this->faker->year,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
