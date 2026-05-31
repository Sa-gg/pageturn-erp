<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = session('user');

        if (!$user) {
            return redirect('/login')->with('error', 'Please log in to access this page.');
        }

        // Super admin can access anything
        if (isset($user['role']) && $user['role'] === 'super_admin') {
            return $next($request);
        }

        // Generic admin access
        if ($role === 'admin') {
            if (!in_array($user['role'] ?? '', ['super_admin', 'catalog_admin', 'inventory_admin', 'orders_admin', 'finance_admin', 'admin', 'staff'])) {
                return redirect('/')->with('error', 'You do not have permission to access the admin dashboard.');
            }
            return $next($request);
        }

        // Specific role check
        if (!isset($user['role']) || $user['role'] !== $role) {
            return redirect('/admin/dashboard')->with('error', "Access Denied: This section requires {$role} privileges.");
        }

        return $next($request);
    }
}
