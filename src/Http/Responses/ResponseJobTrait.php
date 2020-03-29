<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

trait ResponseJobTrait
{
    /**
     *
     * @param ResponseInterface $response
     * @return JsonResponse
     */
    protected function runResponse(ResponseInterface $response): JsonResponse
    {
        return $response->handle();
    }
}

