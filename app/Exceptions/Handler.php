<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
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

    // public function render($request, $exception)
    // {
    //     if ($exception instanceof NotFoundHttpException) {
    //         return redirect('/');
    //     }

    //     return parent::render($request, $exception);
    // }
}
