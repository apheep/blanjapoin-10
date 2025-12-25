<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
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
        // Di production, sembunyikan detail error
        if (config('app.env') === 'production' || !config('app.debug')) {
            // Untuk MethodNotAllowedHttpException (405)
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                // Log error 405 dengan detail lengkap dan mudah dipahami
                $allowedMethods = $e->getHeaders()['Allow'] ?? 'N/A';
                $usedMethod = $request->method();
                
                Log::warning("═══════════════════════════════════════════════════════════");
                Log::warning("🚫 ERROR 405: METHOD NOT ALLOWED");
                Log::warning("═══════════════════════════════════════════════════════════");
                Log::warning("📌 PENJELASAN: Request menggunakan method yang tidak diizinkan untuk route ini");
                Log::warning("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                Log::warning("📍 URL: {$request->fullUrl()}");
                Log::warning("🔧 Method yang digunakan: {$usedMethod}");
                Log::warning("✅ Method yang diizinkan: {$allowedMethods}");
                Log::warning("🌐 IP Address: {$request->ip()}");
                Log::warning("👤 User Agent: {$request->userAgent()}");
                Log::warning("⏰ Waktu: " . now()->format('Y-m-d H:i:s'));
                Log::warning("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                Log::warning("💡 SOLUSI: Pastikan request menggunakan method yang benar");
                Log::warning("   Contoh: Jika route hanya menerima POST, jangan gunakan GET");
                Log::warning("═══════════════════════════════════════════════════════════");

                // Jika request ke login/send-otp, redirect ke login
                if ($request->is('login/send-otp')) {
                    return redirect()->route('login')->with('error', 'Silakan gunakan form untuk mengirim OTP.');
                }
                // Untuk route lain, tampilkan error page 405
                return response()->view('errors.405', [], 405);
            }

            // Untuk 404 Not Found
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                // Log error 404 (optional, bisa di-comment jika terlalu banyak)
                // Log::info('HTTP 404 Not Found', [
                //     'url' => $request->fullUrl(),
                //     'method' => $request->method(),
                //     'ip' => $request->ip(),
                // ]);
                return response()->view('errors.404', [], 404);
            }

            // Untuk error lainnya (500, dll), log dengan detail lengkap dan mudah dipahami
            $exceptionClass = get_class($e);
            $exceptionMessage = $e->getMessage();
            $exceptionFile = $e->getFile();
            $exceptionLine = $e->getLine();
            
            Log::error("═══════════════════════════════════════════════════════════");
            Log::error("❌ ERROR 500: INTERNAL SERVER ERROR");
            Log::error("═══════════════════════════════════════════════════════════");
            Log::error("📌 PENJELASAN: Terjadi kesalahan pada server saat memproses request");
            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::error("📍 URL: {$request->fullUrl()}");
            Log::error("🔧 HTTP Method: {$request->method()}");
            Log::error("🌐 IP Address: {$request->ip()}");
            Log::error("👤 User Agent: {$request->userAgent()}");
            Log::error("⏰ Waktu: " . now()->format('Y-m-d H:i:s'));
            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::error("🔴 JENIS ERROR:");
            Log::error("   Exception Class: {$exceptionClass}");
            Log::error("   Error Message: {$exceptionMessage}");
            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::error("📂 LOKASI ERROR:");
            Log::error("   File: {$exceptionFile}");
            Log::error("   Line: {$exceptionLine}");
            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::error("📋 REQUEST DATA:");
            Log::error("   Input: " . json_encode($request->except(['password', 'password_confirmation']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::error("🔍 STACK TRACE:");
            Log::error($e->getTraceAsString());
            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::error("💡 TINDAKAN:");
            Log::error("   1. Periksa file dan line yang disebutkan di atas");
            Log::error("   2. Periksa request data untuk melihat input yang menyebabkan error");
            Log::error("   3. Periksa stack trace untuk melihat alur eksekusi");
            Log::error("   4. Pastikan semua dependency dan konfigurasi sudah benar");
            Log::error("═══════════════════════════════════════════════════════════");

            // Tampilkan error page sederhana
            return response()->view('errors.500', [], 500);
        }

        // Di development, tampilkan error detail seperti biasa
        return parent::render($request, $e);
    }
}
