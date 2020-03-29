<?php

namespace Tests\Feature\User;

/**
 *
 * @group api
 * @group UserController
 */
use App\Data\Models\User;
use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondSuccessJson;
use App\Http\Responses\RespondUnauthorizedJson;
use App\Http\Responses\RespondValidationErrorsJson;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\ApiTestCase;
use App\Http\Responses\RespondBadRequestJson;

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
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
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
        $response->assertStatus((new RespondBadRequestJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function updateFailureNoFirstname(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->email,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function updateFailureFirstnameTooLong(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
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
    public function updateFailureNoLastname(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
            'firstname' => $this->faker->firstName,
            'email' => $this->faker->email,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function updateFailureLastnameTooLong(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
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
    public function updateFailureNoEmail(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
        ], $this->getBearerHeader($token));
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
    }

    /**
     *
     * @test
     */
    public function updateFailureEmailTooLong(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
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
    public function updateFailureBadEmail(): void
    {
        $token = $this->setAdminAndJwtToken();
        $user = factory(User::class)->create();
        $response = $this->putRequest($this->apiRoute, [
            'id' => $user->id
        ], [
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
        $response->assertStatus((new RespondValidationErrorsJson())->getResponseHeader());
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
        $response->assertStatus((new RespondForbiddenJson())->getResponseHeader());
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
        $response->assertStatus((new RespondUnauthorizedJson())->getResponseHeader());
    }
}
