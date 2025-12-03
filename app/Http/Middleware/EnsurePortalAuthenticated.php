<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePortalAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('portal')->check()) {
            return $next($request);
        }

        $request->session()->put('portal.intended', $request->fullUrl());

        return redirect()->route('portal.login', [
            'returnTo' => $request->fullUrl(),
        ]);
    }
}

