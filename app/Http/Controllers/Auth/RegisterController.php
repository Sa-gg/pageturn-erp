<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users via the
    | Auth microservice and stores the returned token in the session.
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
        $this->middleware('guest');
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request via the auth microservice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
        ]);

        $result = $this->authService->register([
            'name'                  => $request->input('name'),
            'email'                 => $request->input('email'),
            'password'              => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
            'phone'                 => $request->input('phone'),
            'address'               => $request->input('address'),
        ]);

        if (!$result['success']) {
            $errors = $result['errors'] ?? [];
            if (empty($errors)) {
                $errors = ['email' => [$result['message']]];
            }
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors($errors);
        }

        // Store token and user data in session
        $request->session()->put('api_token', $result['data']['token']);
        $request->session()->put('user', $result['data']['user']);

        return redirect('/home')->with('status', 'Welcome to PageTurn Books! Your account has been created.');
    }
}
