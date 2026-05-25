<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        
        $categories = [];
        
        try {
            $response = $this->catalogService->getCategories(['page' => $page, 'search' => $search]);
            if ($response->successful()) {
                $categories = $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch categories for admin: ' . $e->getMessage());
        }

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $response = $this->catalogService->createCategory($data);
            if ($response->successful()) {
                return back()->with('success', 'Category created successfully.');
            }
            return back()->with('error', 'Failed to create category. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to create category: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Catalog service.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $response = $this->catalogService->updateCategory($id, $data);
            if ($response->successful()) {
                return back()->with('success', 'Category updated successfully.');
            }
            return back()->with('error', 'Failed to update category. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to update category: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Catalog service.');
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->catalogService->deleteCategory($id);
            if ($response->successful()) {
                return back()->with('success', 'Category deleted successfully.');
            }
            return back()->with('error', 'Failed to delete category.');
        } catch (\Exception $e) {
            Log::error('Failed to delete category: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Catalog service.');
        }
    }
}
