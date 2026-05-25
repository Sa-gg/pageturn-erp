<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_admin_dashboard_renders()
    {
        // Bypass auth and admin middleware for test
        $this->withoutMiddleware();
        
        // Mock session with token
        $this->withSession(['user' => ['name' => 'Admin Test', 'api_token' => 'fake-token']]);

        // Hit dashboard route
        $response = $this->get('/admin/dashboard');

        // Since backend microservices are not running, it should handle exceptions gracefully and render with 0 values
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
        $response->assertSee('₱0.00'); // total revenue fallback
    }
}
