<?php
namespace Tests;

use App\Data\Models\User;
use App\Data\Models\User\Token;
use App\Repositories\Interfaces\User\AuthRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\App;
use Illuminate\Testing\TestResponse;

class ApiTestCase extends AbstractTestCase
{
    use DatabaseTransactions;

    /**
     *
     * @param string $routeName
     * @param array $routeParams
     * @param array $data
     * @param array $headers
     * @return TestResponse
     */
    protected function postRequest(string $routeName, array $routeParams = [], array $data = [], array $headers = []): TestResponse
    {
        return $this->post($this->routeApi($routeName, $routeParams), $data, $headers);
    }

    /**
     *
     * @param string $routeName
     * @param array $routeParams
     * @param array $headers
     * @return TestResponse
     */
    protected function getRequest(string $routeName, array $routeParams = [], array $headers = []): TestResponse
    {
        return $this->get($this->routeApi($routeName, $routeParams), $headers);
    }

    /**
     *
     * @param string $routeName
     * @param array $routeParams
     * @param array $data
     * @param array $headers
     * @return TestResponse
     */
    protected function patchRequest(string $routeName, array $routeParams = [], array $data = [], array $headers = []): TestResponse
    {
        return $this->patch($this->routeApi($routeName, $routeParams), $data, $headers);
    }

    /**
     *
     * @param string $routeName
     * @param array $routeParams
     * @param array $data
     * @param array $headers
     * @return TestResponse
     */
    protected function putRequest(string $routeName, array $routeParams = [], array $data = [], array $headers = []): TestResponse
    {
        return $this->put($this->routeApi($routeName, $routeParams), $data, $headers);
    }

    /**
     *
     * @param string $routeName
     * @param array $routeParams
     * @param array $headers
     * @return TestResponse
     */
    protected function deleteRequest(string $routeName, array $routeParams = [], array $headers = []):TestResponse
    {
        return $this->delete($this->routeApi($routeName, $routeParams), [], $headers);
    }

    /**
     *
     * @return string
     */
    protected function setAdminAndJwtToken(): string
    {
        $password = 'qwerty123';
        $user = $this->setLoggedUser($password);
        return $this->setJwtToken($user, $password);
    }

    /**
     *
     * @param string $password
     * @return User
     */
    protected function setLoggedUser(string $password): User
    {
        $authUser = factory(User::class)->create([
            'password' => $password
        ]);
        $this->be($authUser);
        return $authUser;
    }

    /**
     *
     * @param User $user
     * @param string $password
     * @return string
     */
    protected function setJwtToken(User $user, string $password): string
    {
        $authRepository = App::make(AuthRepositoryInterface::class);
        $secret = Token::buildNewSecret();
        factory(Token::class)->create(
            [
                'user_id' => $user->id,
                'secret' => $secret,
                'type' => Token::getTypeRefresh()
            ]
        );
        return $authRepository
            ->authAttempt(
                [
                    'email' => $user->email,
                    'password' => $password,
                ],
                $secret,
                Token::getAccessTtl());
    }

    /**
     *
     * @param string $token
     * @return array
     */
    protected function getBearerHeader(string $token): array
    {
        return [
            'Authorization' => "Bearer {$token}"
        ];
    }

    /**
     *
     * @param string $name
     * @param array $parameters
     * @return string
     */
    private function routeApi(string $name, array $parameters): string
    {
        return app('url')->route($name, $parameters);
    }
}
