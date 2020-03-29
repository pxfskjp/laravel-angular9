<?php

namespace Tests\Feature\User;

use App\Data\Models\User;
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
        $response->assertStatus((new RespondSuccessJson())->getResponseHeader());
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
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureNoFirstname(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureFirstnameTooLong(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'firstname' => $this->faker->lexify(\str_repeat('?', 51)),
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureNoLastname(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'firstname' => $this->faker->firstName,
            'email' => $this->faker->email,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureLastnameTooLong(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lexify(\str_repeat('?', 51)),
            'email' => $this->faker->email,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureNoEmail(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureEmailTooLong(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->lexify(\str_repeat('?', 256)),
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function storeFailureBadEmail(): void
    {
        $token = $this->setAdminAndJwtToken();
        $response = $this->postRequest($this->apiRoute, [], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->word,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
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
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
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
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
