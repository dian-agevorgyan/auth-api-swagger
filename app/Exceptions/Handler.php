<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e)
    {
        if (Str::contains($e->getMessage(), 'Duplicate entry')) {
            return response()->json([
                'message' => 'Duplicate entry',
                'errors' => $e->getMessage()
            ], 400);
        }

        $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $statusCode =  $e->getCode();

        $details = [
            'message' => $e->getMessage(),
        ];

        if ($e instanceof ValidationException) {
            $httpCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            $statusCode = BusinessLogicException::VALIDATION_FAILED;
            $details['message'] = $e->getMessage();
            foreach ($e->errors() as $key => $error) {
                $details['errors'][$key] = $error[0] ?? 'Unknown error';
            }
        }

        if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            $httpCode = Response::HTTP_NOT_FOUND;
            $statusCode = Response::HTTP_NOT_FOUND;
            $details['message'] = 'Not Found';
        }

        if ($e instanceof BadRequestHttpException) {
            $httpCode = Response::HTTP_BAD_REQUEST;
            $statusCode = Response::HTTP_BAD_REQUEST;
            $details['message'] = 'Bad Request';
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            $httpCode = Response::HTTP_METHOD_NOT_ALLOWED;
            $statusCode = Response::HTTP_METHOD_NOT_ALLOWED;
            $details['message'] = 'Method Not Allowed';
        }

        if ($e instanceof AuthorizationException) {
            $httpCode = Response::HTTP_UNAUTHORIZED;
            $statusCode = Response::HTTP_UNAUTHORIZED;
            $details['message'] = 'Unauthorized';
        }

        if ($e instanceof BusinessLogicException) {
            $httpCode = $e->getHttpStatusCode();
            $statusCode = $e->getStatus();
            $details['message'] = $e->getStatusMessage();
        }

        $data = [
            'status'  => $statusCode,
            'errors' => $details,
        ];

        if (str_starts_with($httpCode, 5) && !config('app.debug')) {
            $data['errors'] = [
                'message' => 'Server error',
            ];
        }

        return response()->json($data, $httpCode);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
