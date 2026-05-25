<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
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
        
        $books = [];
        $categories = [];
        $authors = [];
        
        try {
            $response = $this->catalogService->getBooks(['page' => $page, 'search' => $search, 'limit' => 15]);
            if ($response->successful()) {
                $books = $response->json();
            }
            
            $catRes = $this->catalogService->getCategories();
            if ($catRes->successful()) {
                $categories = $catRes->json();
            }
            
            $authRes = $this->catalogService->getAuthors();
            if ($authRes->successful()) {
                $authors = $authRes->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch books for admin: ' . $e->getMessage());
        }

        return view('admin.books.index', compact('books', 'categories', 'authors', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:20',
            'author_id' => 'required|integer',
            'category_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'cover_image_url' => 'nullable|url',
        ]);

        try {
            $response = $this->catalogService->createBook($data);
            if ($response->successful()) {
                return redirect('/admin/books')->with('success', 'Book created successfully.');
            }
            return back()->with('error', 'Failed to create book. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to create book: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Catalog service.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:20',
            'author_id' => 'required|integer',
            'category_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'cover_image_url' => 'nullable|url',
        ]);

        try {
            $response = $this->catalogService->updateBook($id, $data);
            if ($response->successful()) {
                return redirect('/admin/books')->with('success', 'Book updated successfully.');
            }
            return back()->with('error', 'Failed to update book. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to update book: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Catalog service.');
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->catalogService->deleteBook($id);
            if ($response->successful()) {
                return redirect('/admin/books')->with('success', 'Book deleted successfully.');
            }
            return back()->with('error', 'Failed to delete book.');
        } catch (\Exception $e) {
            Log::error('Failed to delete book: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Catalog service.');
        }
    }
}
