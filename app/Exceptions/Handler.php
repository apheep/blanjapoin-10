<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Throwable;

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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Handle AuthenticationException FIRST (sebelum production error handling)
        // Ini penting agar redirect ke login tidak tertangkap sebagai error 500
        if ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        }

        // Handle TokenMismatchException (CSRF token expired)
        if ($e instanceof TokenMismatchException) {
            // Redirect ke halaman login biasa dengan pesan error
            return redirect()->route('login')
                ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }


        // Di production, sembunyikan detail error
        if (config('app.env') === 'production' || !config('app.debug')) {
            // Untuk MethodNotAllowedHttpException (405)
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                // Jika request ke login/send-otp, redirect ke login
                if ($request->is('login/send-otp')) {
                    return redirect()->route('login')->with('error', 'Silakan gunakan form untuk mengirim OTP.');
                }
                // Untuk route lain, tampilkan error page 405
                return response()->view('errors.405', [], 405);
            }

            // Untuk 404 Not Found
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->view('errors.404', [], 404);
            }

            // Untuk error lainnya, tampilkan error page sederhana
            return response()->view('errors.500', [], 500);
        }

        // Di development, tampilkan error detail seperti biasa
        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Jika request expects JSON, return JSON response
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Redirect semua ke halaman login biasa
        return redirect()->guest(route('login'))
            ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
    }
}
