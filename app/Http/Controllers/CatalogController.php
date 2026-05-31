<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CatalogController extends Controller
{
    protected CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function index(Request $request)
    {
        $books = [];
        
        $params = $request->only(['category_id', 'author_id', 'search', 'page']);
        
        try {
            $response = $this->catalogService->getBooks($params);
            if ($response->successful()) {
                $books = $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Catalog service unavailable (books): ' . $e->getMessage());
        }

        return view('catalog.index', compact('books'));
    }

    public function show($id)
    {
        $book = null;
        try {
            $response = $this->catalogService->getBook($id);
            if ($response->successful()) {
                $book = $response->json('data') ?? $response->json('book') ?? $response->json();

                if (empty($book) || !is_array($book)) {
                    abort(404, 'Book not found');
                }
            } else {
                abort(404, 'Book not found');
            }
        } catch (\Exception $e) {
            Log::error('Catalog service unavailable (book ' . $id . '): ' . $e->getMessage());
            abort(503, 'Catalog Service is temporarily unavailable.');
        }

        return view('catalog.show', compact('book'));
    }
}
