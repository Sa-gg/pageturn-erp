<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateToken
{
    /**
     * Handle an incoming request.
     * Checks for a valid API token in the session. If not found, redirects to login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$roles  Optional role restrictions
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $token = $request->session()->get('api_token');
        $user = $request->session()->get('user');

        if (!$token || !$user) {
            return redirect('/login')->with('error', 'Please log in to continue.');
        }

        // Check role if specified
        if (!empty($roles) && !in_array($user['role'], $roles)) {
            abort(403, 'Forbidden. You do not have permission to access this page.');
        }

        // Share user data with all views
        view()->share('authUser', $user);
        view()->share('apiToken', $token);

        return $next($request);
    }
}
