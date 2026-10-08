<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class Operasional
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Authentication check FIRST
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Authorization check
        if (Auth::user()->level !== 'operasional') {
            abort(404);
        }

        // 3. Share user level with views
        View::share('levelnya', Auth::user()->level);

        // 4. Continue request
        return $next($request);
    }
}