<?php

namespace Tests\Feature\Hardware;

use App\Data\Models\Hardware;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;

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
     * @return array
     */
    public function updateData(): array
    {
        $this->refreshApplication();
        $this->setUpFaker();
        return [
            [
                [
                    'serial_number' => $this->faker->uuid,
                    'production_year' => $this->faker->year,
                ]
            ],
            [
                [
                    'name' => $this->faker->lexify(\str_repeat('?', 101)),
                    'serial_number' => $this->faker->uuid,
                    'production_year' => $this->faker->year,
                ]
            ],
            [
                [
                    'name' => $this->faker->company,
                    'production_year' => $this->faker->year,
                ]
            ],
            [
                [
                    'name' => $this->faker->company,
                    'serial_number' => $this->faker->lexify(\str_repeat('?', 101)),
                    'production_year' => $this->faker->year,
                ]
            ],
            [
                [
                    'name' => $this->faker->company,
                    'serial_number' => $this->faker->uuid,
                ]
            ]
        ];
    }

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
        $response->assertOk();
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
        $response->assertStatus(Response::HTTP_FORBIDDEN);
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
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     *
     * @test
     * @dataProvider updateData
     */
    public function updateFailureBadData(array $data): void
    {
        $token = $this->setAdminAndJwtToken();
        $hardware = factory(Hardware::class)->create();
        $response = $this->putRequest($this->apiRoute, ['id' => $hardware->id], $data, $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
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
        $response->assertStatus(Response::HTTP_FORBIDDEN);
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
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
