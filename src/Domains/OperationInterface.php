<?php

namespace App\Domains;

use App\Data\Models\User;
use Illuminate\Http\JsonResponse;

interface OperationInterface
{

    /**
     *
     * @param null|User $authUser
     * @return JsonResponse
     */
    public function handle(?User $authUser): JsonResponse;
}
