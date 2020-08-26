<?php

namespace App\Http\Controllers;

use App\Data\Models\User;
use App\Domains\Transfer\DeleteOperation;
use App\Domains\Transfer\ListOperation;
use App\Domains\Transfer\StoreOperation;
use App\Domains\Transfer\UpdateOperation;
use Illuminate\Http\JsonResponse;

final class TransferController extends Controller
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
