<?php

namespace Tests\Feature\Hardware;

use App\Data\Models\Hardware;
use App\Repositories\Interfaces\HardwareRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;
use Mockery;

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
     * @return array
     */
    public function storeData(): array
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
        $response->assertOK();
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
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     * @test
     * @dataProvider storeData
     */
    public function storeFailureBadData(array $data): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], $data, $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
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
        $response->assertStatus(Response::HTTP_FORBIDDEN);
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
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
