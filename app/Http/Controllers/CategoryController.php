<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function index()
    {
        $categories = [];

        try {
            $response = $this->catalogService->getCategories();
            if ($response->successful()) {
                $categories = $response->json('categories') ?? $response->json() ?? [];
            }
        } catch (\Exception $e) {
            Log::error('Catalog service unavailable (categories): ' . $e->getMessage());
        }

        return view('catalog.categories', compact('categories'));
    }
}
