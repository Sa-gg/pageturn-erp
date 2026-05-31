<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class AuthService
{
    protected string $baseUrl;

    protected function token(): ?string
    {
        $token = session('user.api_token') ?? session('token') ?? session('api_token');
        return is_string($token) ? $token : null;
    }

    protected function client(): PendingRequest
    {
        $token = $this->token();
        return $token ? Http::withToken($token) : Http::acceptJson();
    }

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
        return $this->client()->get("{$this->baseUrl}/users", $params);
    }

    public function createUser($data)
    {
        return $this->client()->post("{$this->baseUrl}/users", $data);
    }

    public function updateUser($id, $data)
    {
        return $this->client()->put("{$this->baseUrl}/users/{$id}", $data);
    }

    public function deleteUser($id)
    {
        return $this->client()->delete("{$this->baseUrl}/users/{$id}");
    }
}
