<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FrontendTest extends TestCase
{
    public function test_homepage_renders_successfully()
    {
        // Fake the Catalog service call
        Http::fake([
            '*/api/books*' => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'title' => 'Laravel Masterclass',
                        'price' => 500.00,
                        'cover_image_url' => 'https://via.placeholder.com/150',
                        'author' => ['name' => 'John Doe'],
                        'category' => ['name' => 'Tech'],
                        'is_available' => true,
                    ]
                ]
            ], 200),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('PageTurn');
        $response->assertSee('Laravel Masterclass');
    }

    public function test_catalog_renders_successfully()
    {
        Http::fake([
            '*/api/books*' => Http::response([
                'data' => [],
                'links' => [],
                'meta' => []
            ], 200),
            '*/api/categories' => Http::response([], 200),
            '*/api/authors' => Http::response([], 200),
        ]);

        $response = $this->get('/catalog');

        $response->assertStatus(200);
        $response->assertSee('Browse Catalog');
    }

    public function test_auth_pages_render_successfully()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login');

        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Register');
    }

    public function test_cart_page_renders_successfully()
    {
        $response = $this->get('/cart');
        $response->assertStatus(200);
        $response->assertSee('Shopping Cart');
    }
}
