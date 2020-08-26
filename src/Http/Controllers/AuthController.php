<?php

namespace App\Http\Controllers;

use App\Data\Models\User;
use App\Domains\User\Auth\LoginOperation;
use App\Domains\User\Auth\LogoutOperation;
use App\Domains\User\Auth\PingOperation;
use App\Domains\User\Auth\RefreshTokenOperation;
use Illuminate\Http\JsonResponse;

final class AuthController extends Controller
{
    /**
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        return $this->serve(LoginOperation::class);
    }

    /**
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return $this->withAuthenticate(fn (User $user) => $this->serve(LogoutOperation::class, $user));
    }

    /**
     *
     * @return mixed
     */
    public function pingUser()
    {
        return $this->withAuthenticate(fn () => $this->serve(PingOperation::class));
    }

    /**
     *
     * @return JsonResponse
     */
    public function refreshToken(): JsonResponse
    {
        return $this->serve(RefreshTokenOperation::class);
    }
}
