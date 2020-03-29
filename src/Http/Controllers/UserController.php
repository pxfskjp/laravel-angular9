<?php

namespace App\Http\Controllers;

use App\Data\Models\User;
use App\Domains\User\{
    DeleteOperation,
    ListOperation,
    StoreOperation,
    UpdateOperation
};
use Illuminate\Http\JsonResponse;

final class UserController extends Controller
{

    /**
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->withAuthenticate(function (User $user) {
            return $this->serve(ListOperation::class, $user);
        });
    }

    /**
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        return $this->withAuthenticate(function (User $user) {
            return $this->serve(StoreOperation::class, $user);
        });
    }

    /**
     *
     * @return JsonResponse
     */
    public function update(): JsonResponse
    {
        return $this->withAuthenticate(function (User $user) {
            return $this->serve(UpdateOperation::class, $user);
        });
    }

    /**
     *
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        return $this->withAuthenticate(function (User $user) {
            return $this->serve(DeleteOperation::class, $user);
        });
    }
}
