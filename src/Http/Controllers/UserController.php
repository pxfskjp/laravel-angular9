<?php

namespace App\Http\Controllers;

use App\Data\Models\User;
use App\Domains\User\DeleteOperation;
use App\Domains\User\ListOperation;
use App\Domains\User\StoreOperation;
use App\Domains\User\UpdateOperation;
use Illuminate\Http\JsonResponse;

final class UserController extends Controller
{

    /**
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->withAuthenticate(fn (User $user) => $this->serve(ListOperation::class, $user));
    }

    /**
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        return $this->withAuthenticate(fn (User $user) => $this->serve(StoreOperation::class, $user));
    }

    /**
     *
     * @return JsonResponse
     */
    public function update(): JsonResponse
    {
        return $this->withAuthenticate(fn (User $user) => $this->serve(UpdateOperation::class, $user));
    }

    /**
     *
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        return $this->withAuthenticate(fn (User $user) => $this->serve(DeleteOperation::class, $user));
    }
}
