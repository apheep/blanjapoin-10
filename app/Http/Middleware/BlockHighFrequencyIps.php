<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ClickHistory;
use Carbon\Carbon;

class BlockHighFrequencyIps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $today = Carbon::today();

        $count = ClickHistory::where('ip_address', $ip)
            ->whereDate('clicked_at', $today)
            ->count();

        if ($count > 100) {
            abort(403, 'Maaf terdeteksi Bot');
        }

        return $next($request);
    }
}
