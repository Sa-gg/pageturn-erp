<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users via the Auth microservice
    | and manages session-based token storage for the frontend.
    |
    */

    protected $authService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authService = new AuthService();
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request via the auth microservice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $result = $this->authService->login(
            $request->input('email'),
            $request->input('password')
        );

        if (!$result['success']) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => $result['message']]);
        }

        // Store token and user data in session
        $request->session()->put('api_token', $result['data']['token']);
        $request->session()->put('user', $result['data']['user']);

        // Redirect based on role
        $user = $result['data']['user'];
            $adminRoles = ['admin', 'super_admin', 'catalog_admin', 'orders_admin', 'inventory_admin', 'finance_admin', 'staff'];
            if (in_array($user['role'], $adminRoles)) {
                return redirect('/admin/dashboard');
            }

            return redirect('/');
    }

    /**
     * Log the user out via the auth microservice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $token = $request->session()->get('api_token');

        if ($token) {
            $this->authService->logout($token);
        }

        $request->session()->forget(['api_token', 'user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'You have been logged out.');
    }
}
