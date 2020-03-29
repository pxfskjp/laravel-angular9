<?php

namespace App\Domains\User\Auth;

use App\Data\Models\User;
use App\Domains\AbstractOperation;
use App\Http\Responses\RespondSuccessJson;
use Illuminate\Http\JsonResponse;

final class PingOperation extends AbstractOperation
{

    /**
     *
     * {@inheritdoc}
     * @see \App\Domains\OperationInterface::handle()
     */
    public function handle(?User $authUser): JsonResponse
    {
        return $this->runResponse(new RespondSuccessJson('is loggedin'));
    }
}
