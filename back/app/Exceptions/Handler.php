<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (\Throwable $e) {});
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request    $request
     * @param \Throwable $e
     *
     * @return Response
     *
     * @throws \Throwable
     */
    public function render($request, \Throwable $e)
    {
        if ($request->wantsJson()) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json('', 404);
            }
        }

        return parent::render($request, $e);
    }
}
