<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class CatalogService
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
        $this->baseUrl = config('services.catalog.base_url', 'http://localhost:8002/api');
    }

    public function getBooks($params = [])
    {
        return Http::get("{$this->baseUrl}/books", $params);
    }

    public function getBook($id)
    {
        return Http::get("{$this->baseUrl}/books/{$id}");
    }

    public function getCategories($params = [])
    {
        return Http::get("{$this->baseUrl}/categories", $params);
    }

    public function getAuthors($params = [])
    {
        return Http::get("{$this->baseUrl}/authors", $params);
    }

    public function createAuthor($data)
    {
        return $this->client()->post("{$this->baseUrl}/admin/authors", $data);
    }

    public function updateAuthor($id, $data)
    {
        return $this->client()->put("{$this->baseUrl}/admin/authors/{$id}", $data);
    }

    public function deleteAuthor($id)
    {
        return $this->client()->delete("{$this->baseUrl}/admin/authors/{$id}");
    }

    public function createCategory($data)
    {
        return $this->client()->post("{$this->baseUrl}/admin/categories", $data);
    }

    public function updateCategory($id, $data)
    {
        return $this->client()->put("{$this->baseUrl}/admin/categories/{$id}", $data);
    }

    public function deleteCategory($id)
    {
        return $this->client()->delete("{$this->baseUrl}/admin/categories/{$id}");
    }

    public function createBook($data)
    {
        return $this->client()->post("{$this->baseUrl}/admin/books", $data);
    }

    public function updateBook($id, $data)
    {
        return $this->client()->put("{$this->baseUrl}/admin/books/{$id}", $data);
    }

    public function deleteBook($id)
    {
        return $this->client()->delete("{$this->baseUrl}/admin/books/{$id}");
    }
}
