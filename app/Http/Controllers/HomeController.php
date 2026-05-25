<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function index()
    {
        $featuredBooks = [];
        
        try {
            $response = $this->catalogService->getBooks(['limit' => 4]);
            if ($response->successful()) {
                $featuredBooks = $response->json('data') ?? [];
            }
        } catch (\Exception $e) {
            Log::error('Catalog service unavailable: ' . $e->getMessage());
        }

        return view('home', compact('featuredBooks'));
    }
}
