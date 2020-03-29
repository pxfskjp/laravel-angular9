<?php

namespace App\Http\Controllers;

use App\Data\Models\User;
use App\Http\Responses\RespondForbiddenJson;
use App\Http\Responses\RespondUnauthorizedJson;
use Framework\Http\Controllers\Controller as FrameworkController;
use Illuminate\Support\Facades\App;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Closure;

class Controller extends FrameworkController
{

    /**
     *
     * @param string $taskClass
     * @param User $user
     * @return mixed
     */
    protected function serve(string $taskClass, User $user = null)
    {
        $task = App::make($taskClass);
        return $task->handle($user);
    }

    /**
     *
     * @param \Closure $callback
     * @return mixed
     */
    protected function withAuthenticate(Closure $callback)
    {
        $auth = App::make(JWTAuth::class);
        try {
            $user = $auth->parseToken()->authenticate();
            if ($user instanceof User) {
                return $callback($user);
            }
            return $this->serve(RespondForbiddenJson::class);
        } catch (TokenExpiredException $e) {
            return $this->serve(RespondUnauthorizedJson::class);
        } catch (TokenInvalidException | JWTException $e) {
            return $this->serve(RespondForbiddenJson::class);
        }
    }
}
