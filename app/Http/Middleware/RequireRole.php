<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RequireRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(['message' => 'Unauthorized. No token provided.'], 401);
        }

        try {
            $response = Http::withToken($token)->get(env('AUTH_SERVICE_URL') . '/auth/validate');
            
            if (!$response->successful() || !$response->json('valid')) {
                return response()->json(['message' => 'Unauthorized. Invalid token.'], 401);
            }
            
            $user = $response->json('user');
            
            if ($user['role'] !== 'super_admin' && $user['role'] !== $role && $user['role'] !== 'admin') {
                return response()->json(['message' => 'Forbidden. Insufficient permissions.'], 403);
            }
            
            $request->merge(['auth_user' => $user]);
            return $next($request);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error. Auth service unavailable.'], 500);
        }
    }
}
