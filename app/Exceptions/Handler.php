<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            switch ($exception->getStatusCode()) {
                case 404:
                    return response()->view('errors.404', [], 404);
                    break;
                case 500:
                    return response()->view('errors.500', [], 500);
                    break;
                // tambahkan kasus lain jika diperlukan
                default:
                    return $this->renderHttpException($exception);
                    break;
            }
        }

        return parent::render($request, $exception);
    }
}
