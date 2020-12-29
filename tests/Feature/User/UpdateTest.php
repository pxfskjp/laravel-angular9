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
class UpdateTest extends ApiTestCase
{
    use WithFaker;

    /**
     *
     * @var string $apiRoute
     */
    private $apiRoute = 'user.update';

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
    public function updateSuccess(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
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
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
        ], $this->getBearerHeader(''));
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     *
     * @test
     */
    public function updateFailureBadUser(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id + 1
        ], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
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
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, ['id' => $user->id], $data, $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     *
     * @test
     */
    public function updateFailureEmailNoUnique(): void
    {
        $token = $this->setAdminAndJwtToken();
        $userOne = factory(User::class)->create();
        $userTwo = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $userOne->id
        ], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $userTwo->email,
        ], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**
     *
     * @test
     */
    public function updateFailureBadToken(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
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
    public function updateFailureTokenExpired(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $now = Carbon::now();
        $now->addMinutes(61);
        Carbon::setTestNow($now);
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
        ], $this->getBearerHeader($token));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
