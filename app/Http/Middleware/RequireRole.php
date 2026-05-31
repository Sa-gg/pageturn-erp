<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RequireRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $allowInternal = func_num_args() >= 4 && func_get_arg(3) === 'allow_internal';
        if ($allowInternal) {
            $internalToken = env('INTERNAL_SERVICE_TOKEN');
            $providedToken = $request->header('X-Internal-Token');

            if (!empty($internalToken) && hash_equals($internalToken, (string) $providedToken)) {
                $request->merge(['auth_user' => ['role' => 'internal_service']]);
                return $next($request);
            }
        }

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
            if (!is_array($user) || !isset($user['role']) || !is_string($user['role'])) {
                return response()->json(['message' => 'Unauthorized. Invalid user payload.'], 401);
            }
            
            // Allow super admin or specific role
            if ($user['role'] !== 'super_admin' && $user['role'] !== $role && $user['role'] !== 'admin') {
                return response()->json(['message' => 'Forbidden. Insufficient permissions.'], 403);
            }
            
            // Pass user down to request
            $request->merge(['auth_user' => $user]);
            
            return $next($request);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error. Auth service unavailable.'], 500);
        }
    }
}
