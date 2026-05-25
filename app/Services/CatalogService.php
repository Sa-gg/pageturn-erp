<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CatalogService
{
    protected $baseUrl;

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
        $token = session('user.api_token');
        return Http::withToken($token)->post("{$this->baseUrl}/admin/authors", $data);
    }

    public function updateAuthor($id, $data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->put("{$this->baseUrl}/admin/authors/{$id}", $data);
    }

    public function deleteAuthor($id)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->delete("{$this->baseUrl}/admin/authors/{$id}");
    }

    public function createCategory($data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->post("{$this->baseUrl}/admin/categories", $data);
    }

    public function updateCategory($id, $data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->put("{$this->baseUrl}/admin/categories/{$id}", $data);
    }

    public function deleteCategory($id)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->delete("{$this->baseUrl}/admin/categories/{$id}");
    }

    public function createBook($data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->post("{$this->baseUrl}/admin/books", $data);
    }

    public function updateBook($id, $data)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->put("{$this->baseUrl}/admin/books/{$id}", $data);
    }

    public function deleteBook($id)
    {
        $token = session('user.api_token');
        return Http::withToken($token)->delete("{$this->baseUrl}/admin/books/{$id}");
    }
}
