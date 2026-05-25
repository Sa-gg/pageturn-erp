<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = session('user');

        if (!$user) {
            return redirect('/login')->with('error', 'Please log in to access the admin dashboard.');
        }

        if (!isset($user['role']) || !in_array($user['role'], ['admin', 'staff'])) {
            return redirect('/')->with('error', 'You do not have permission to access the admin dashboard.');
        }

        return $next($request);
    }
}
