<?php

namespace App\Exceptions;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Throwable $exception
     *
     * @return void
     *
     * @throws \Exception
     */

    public function report(Throwable $exception)
    {
        $ignoreable_exception_messages = ['Unauthenticated or Token Expired, Please Login'];
        //        $ignoreable_exception_messages[] = 'The refresh token is invalid.';
        $ignoreable_exception_messages[] = 'The resource owner or authorization server denied the request.';
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            if (!in_array($exception->getMessage(), $ignoreable_exception_messages)) {
                app('sentry')->captureException($exception);
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */

    public function render($request, Throwable $exception)
    {

        if ($request->expectsJson()) {
            if ($exception instanceof PostTooLargeException) {
                return $this->respondError(
                    "Size of attached file should be less " . ini_get("upload_max_filesize") . "B",
                    400
                );
            }

            if ($exception instanceof AuthenticationException) {
                return $this->respondUnAuthenticated('Unauthenticated or Token Expired, Please Login');
            }

            if ($exception instanceof AuthorizationException) {
                return $this->respondUnAuthorized($exception->getMessage());
            }

            if ($exception instanceof ThrottleRequestsException) {
                return $this->respondError(
                    'Too Many Requests,Please Slow Down',
                    429,
                    $exception
                );
            }

            if ($exception instanceof ModelNotFoundException) {
                return $this->respondNotFound('Record not found');
            }

            if ($exception instanceof ValidationException) {

                return $this->respondValidationErrors($exception);
            }
            if ($exception instanceof QueryException) {

                return $this->respondError(
                    'There was Issue with the Query',
                    500,
                    $exception
                );
            }
            // if ($exception instanceof HttpResponseException) {
            //     // $exception = $exception->getResponse();
            //     return $this->apiResponse(
            //         [
            //             'success' => false,
            //             'message' => "There was some internal error",
            //             'exception'  => $exception
            //         ],
            //         500
            //     );
            // }
            if ($exception instanceof \Error) {
                // $exception = $exception->getResponse();
                return $this->apiResponse(
                    [
                        'success' => false,
                        'message' => "There was some internal error",
                        'exception' => $exception
                    ],
                    500
                );
            }
        }


        return parent::render($request, $exception);
    }
}
