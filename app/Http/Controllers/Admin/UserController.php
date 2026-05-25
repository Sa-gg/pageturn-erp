<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $role = $request->get('role', '');
        
        $users = [];
        
        try {
            $params = ['page' => $page];
            if ($role) $params['role'] = $role;
            
            $response = $this->authService->getUsers($params);
            if ($response->successful()) {
                $users = $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch users for admin: ' . $e->getMessage());
        }

        return view('admin.users.index', compact('users', 'role'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff,customer',
        ]);

        try {
            $response = $this->authService->createUser($data);
            if ($response->successful()) {
                return back()->with('success', 'User created successfully.');
            }
            return back()->with('error', 'Failed to create user. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to create user: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Auth service.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:admin,staff,customer',
            'is_active' => 'required|boolean',
        ]);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        try {
            $response = $this->authService->updateUser($id, $data);
            if ($response->successful()) {
                return back()->with('success', 'User updated successfully.');
            }
            return back()->with('error', 'Failed to update user. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to update user: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Auth service.');
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->authService->deleteUser($id);
            if ($response->successful()) {
                return back()->with('success', 'User deleted successfully.');
            }
            return back()->with('error', 'Failed to delete user.');
        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Auth service.');
        }
    }
}
