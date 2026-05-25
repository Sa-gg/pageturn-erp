<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthorController extends Controller
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
        
        $authors = [];
        
        try {
            $response = $this->catalogService->getAuthors(['page' => $page, 'search' => $search]);
            if ($response->successful()) {
                $authors = $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch authors for admin: ' . $e->getMessage());
        }

        return view('admin.authors.index', compact('authors', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        try {
            $response = $this->catalogService->createAuthor($data);
            if ($response->successful()) {
                return back()->with('success', 'Author created successfully.');
            }
            return back()->with('error', 'Failed to create author. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to create author: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Catalog service.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        try {
            $response = $this->catalogService->updateAuthor($id, $data);
            if ($response->successful()) {
                return back()->with('success', 'Author updated successfully.');
            }
            return back()->with('error', 'Failed to update author. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to update author: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Catalog service.');
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->catalogService->deleteAuthor($id);
            if ($response->successful()) {
                return back()->with('success', 'Author deleted successfully.');
            }
            return back()->with('error', 'Failed to delete author.');
        } catch (\Exception $e) {
            Log::error('Failed to delete author: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Catalog service.');
        }
    }
}
