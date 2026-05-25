<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AuthService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.auth.base_url', 'http://localhost:8001/api');
    }

    public function login($email, $password)
    {
        return Http::post("{$this->baseUrl}/auth/login", [
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function register($data)
    {
        return Http::post("{$this->baseUrl}/auth/register", $data);
    }

    public function getUsers($params = [])
    {
        $token = session('user.api_token');
        return Http::withToken($token)->get("{$this->baseUrl}/users", $params);
    }

    public function createUser($data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->post("{$this->baseUrl}/users", $data);
    }

    public function updateUser($id, $data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->put("{$this->baseUrl}/users/{$id}", $data);
    }

    public function deleteUser($id)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->delete("{$this->baseUrl}/users/{$id}");
    }
}
