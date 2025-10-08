<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $lastActivity = Session::get('last_activity');
        $timeout = config('session.lifetime') * 60; // Convert to seconds

        if ($lastActivity && (time() - $lastActivity > $timeout)) {
            Auth::logout();
            Session::flush();
            return redirect()->route('login')->with('session_timeout', 'Your session has expired due to inactivity.');
        }

        Session::put('last_activity', time());

        return $next($request);
    }
}
