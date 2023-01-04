<?php

namespace App\Exceptions;

use App\Http\Traits\ResponseTrait;
use Exception;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    // public function register()
    // {
    //     $this->reportable(function (Throwable $e) {
    //         //
    //     });
    // }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
       return response()->json(['message' => 'Unauthenticated.'], 401);

    }

    // public function render($request, Exception $exception)
    // {
    //     $class = get_class($exception);

    //     switch($class) {
    //         case 'Illuminate\Auth\AuthenticationException':
    //             $guard = array_get($exception->guards(), 0);
    //             switch ($guard) {
    //                 case 'admin':
    //                     $login = 'admin.login';
    //                     break;
    //                 default:
    //                     $login = 'login';
    //                     break;
    //             }

    //             return redirect()->route($login);
    //     }

    //     return parent::render($request, $exception);
    // }

    public function render($request, $exception)
    {
        if ($request->expectsJson()) {
            return match (true) {

                $exception instanceof AuthenticationException => $this->sendError($exception->getMessage(), [],  401),
                $exception instanceof UnauthorizedException => $this->sendError($exception->getMessage(), [],  403),
                $exception instanceof ThrottleRequestsException => $this->sendError($exception->getMessage(), [],  429),
                $exception instanceof ModelNotFoundException ||
                $exception instanceof NotFoundHttpException  => $this->sendError($exception->getMessage(), [],  404),
                $exception instanceof MethodNotAllowedHttpException => $this->sendError($exception->getMessage(), [],  405),
                $exception instanceof ValidationException =>  $this->invalidJson($request, $exception),
                default => $this->sendError($exception->getMessage(), [],  500)
            };
        }

        return parent::render($request, $exception);
    }
}
