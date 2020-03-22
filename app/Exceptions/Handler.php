<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Http\Resource\JsonApiResource;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param \Throwable $exception
     * @throws Exception
     */
    public function report($exception)
    {
        parent::report($exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return Response|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, $exception)
    {
        if ($exception instanceof ValidationException) {
            // return 422 Unprocessable Entity
            return $this->jsonResponse([], [], $exception->errors(), 422);
        }

        //Just return json error as it is an api only application
        if ($exception instanceof AuthorizationException) {
            // return 403 Forbidden
            return $this->jsonResponse([], [], ['message' => 'This action is unauthorized.'], 403);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        //Just return json error as it is an api only application
        return $this->jsonResponse([], [], ['message' => 'User not authenticated.'], 401);
    }

    /**
     * @param array $data
     * @param array $meta
     * @param array $errors
     * @param int $responseCode
     * @return Response
     */
    protected function jsonResponse(array $data, array $meta, array $errors, int $responseCode)
    {
        return new Response([
            'data' => $data,
            'meta' => $meta,
            'errors' => $errors
        ], $responseCode);
    }
}
