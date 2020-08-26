<?php

namespace Tests\Feature\User;

use App\Data\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\ApiTestCase;

/**
 *
 * @group api
 * @group UserController
 */
class StoreTest extends ApiTestCase
{
    use WithFaker;

    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'user.store';

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
                    'lastname' => $this->faker->lastName,
                    'email' => $this->faker->email,
                ]
            ],
            [
                [
                    'firstname' => $this->faker->lexify(\str_repeat('?', 51)),
                    'lastname' => $this->faker->lastName,
                    'email' => $this->faker->email,
                ]
            ],
            [
                [
                    'firstname' => $this->faker->firstName,
                    'email' => $this->faker->email,
                ]
            ],
            [
                [
                    'firstname' => $this->faker->firstName,
                    'lastname' => $this->faker->lexify(\str_repeat('?', 51)),
                    'email' => $this->faker->email,
                ]
            ],
            [
                [
                    'firstname' => $this->faker->firstName,
                    'lastname' => $this->faker->lastName,
                ]
            ],
            [
                [
                    'firstname' => $this->faker->firstName,
                    'lastname' => $this->faker->lastName,
                    'email' => $this->faker->lexify(\str_repeat('?', 256)),
                ]
            ],
            [
                [
                    'firstname' => $this->faker->firstName,
                    'lastname' => $this->faker->lastName,
                    'email' => $this->faker->word,
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
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
        ], $this->getBearerHeader($token));
        $response->assertOk();
        $response->assertJsonStructure([
            'message',
            'result' => [
                'id',
                'firstname',
                'lastname',
                'email',
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
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
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
    public function storeFailureEmailNoUnique(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->postRequest($this->apiRoute, [], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $user->email,
        ], $this->getBearerHeader($token));
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
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
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
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
        ], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
