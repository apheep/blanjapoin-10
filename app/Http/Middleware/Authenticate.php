<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika request adalah AJAX atau expects JSON, return null (tidak redirect)
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return null;
        }
        
        return route('login');
    }
}
