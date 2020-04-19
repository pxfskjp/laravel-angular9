<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractRespondJson extends Response implements ResponseInterface
{
    /**
     *
     * @return JsonResponse
     */
    public function handle(): JsonResponse
    {
        return response()->json($this->prepareResponse(), $this->getResponseHeader());
    }

    /**
     *
     * @return int
     */
    abstract public function getResponseHeader(): int;

    /**
     *
     * @return array
     */
    protected function prepareResponse(): array
    {
        return [
            'result'  => $this->result,
            'message' => $this->message
        ];
    }
}
