<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $response = $this->authService->login($request->email, $request->password);

            if ($response->successful()) {
                $data = $response->json();
                session()->put('token', $data['token']);
                session()->put('user', $data['user']);
                return redirect('/')->with('success', 'Logged in successfully!');
            }

            return back()->with('error', 'Invalid email or password.');
        } catch (\Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            return back()->with('error', 'Authentication service is currently unavailable.');
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);

        try {
            $response = $this->authService->register($request->only('name', 'email', 'password'));

            if ($response->successful()) {
                $data = $response->json();
                session()->put('token', $data['token']);
                session()->put('user', $data['user']);
                return redirect('/')->with('success', 'Account created successfully!');
            }

            return back()->with('error', 'Registration failed: ' . $response->json('message', 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return back()->with('error', 'Authentication service is currently unavailable.');
        }
    }

    public function logout()
    {
        session()->forget(['token', 'user', 'cart']);
        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
