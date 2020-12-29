<?php

namespace App\Exceptions;

use App\Http\Responses\RespondUnauthorizedJson;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use App\Http\Responses\ResponseJsonTrait;

class Handler extends ExceptionHandler
{
    use ResponseJsonTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation'
    ];

    /**
     *
     * {@inheritDoc}
     * @see \Illuminate\Foundation\Exceptions\Handler::render()
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return $this->runResponse(new RespondUnauthorizedJson());
        }
        return response()->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
    }
}
