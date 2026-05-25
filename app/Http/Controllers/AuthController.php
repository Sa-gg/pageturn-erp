<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'is_active' => true,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $token = Str::random(60);
        $user->api_token = hash('sha256', $token);
        $user->save();

        return response()->json([
            'message' => 'Registration successful',
            'user' => $this->formatUser($user),
            'token' => $token,
        ], 201);
    }

    /**
     * Login and return API token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Account is deactivated. Please contact support.',
            ], 403);
        }

        // Generate new token
        $token = Str::random(60);
        $user->api_token = hash('sha256', $token);
        $user->save();

        return response()->json([
            'message' => 'Login successful',
            'user' => $this->formatUser($user),
            'token' => $token,
        ]);
    }

    /**
     * Logout (invalidate token).
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get authenticated user profile.
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $this->formatUser($request->user()),
        ]);
    }

    /**
     * Update own profile.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($request->only(['name', 'phone', 'address']));

        return response()->json([
            'message' => 'Profile updated',
            'user' => $this->formatUser($user),
        ]);
    }

    /**
     * Validate token (inter-service call).
     * Used by other microservices to validate a user's token.
     */
    public function validate(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['valid' => false, 'message' => 'No token provided'], 401);
        }

        $user = User::where('api_token', hash('sha256', $token))->first();

        if (!$user || !$user->is_active) {
            return response()->json(['valid' => false, 'message' => 'Invalid or expired token'], 401);
        }

        return response()->json([
            'valid' => true,
            'user' => $this->formatUser($user),
        ]);
    }

    /**
     * Format user for response.
     */
    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'phone' => $user->phone,
            'address' => $user->address,
            'created_at' => $user->created_at,
        ];
    }
}
