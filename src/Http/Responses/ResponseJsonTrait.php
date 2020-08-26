<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

trait ResponseJsonTrait
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

