<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\BaseException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Throwable $e)
    {
        //没有登录
        if ($e instanceof AuthenticationException) {
            return response()->json(['error' => ['code' => 999, 'message' => '没有登录'], 'status' => 401, 'success' => false], 401);
        }

        //没有权限
        if ($e instanceof AuthorizationException) {
            return response()->json(['error' => ['code' => 1000, 'message' => '没有权限'], 'status' => 403, 'success' => false], 403);
        }

        if ($request->is('api/*')) {
            if ($e instanceof BaseException) {
                return response()->json(['error' => ['code' => $e->getCode(), 'message' => $e->getMessage()], 'status' => $e->getStatus(), 'success' => $e->getSuccess()], $e->getStatus());
            }
        }

        return parent::render($request, $e);
    }
}
