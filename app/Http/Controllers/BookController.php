<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReview;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * List books with filtering, search, and pagination.
     */
    public function index(Request $request)
    {
        $query = Book::with(['category', 'author']);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by author
        if ($request->has('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Filter by format
        if ($request->has('format')) {
            $query->where('format', $request->format);
        }

        // Filter by availability
        if ($request->has('is_available')) {
            $query->where('is_available', $request->boolean('is_available'));
        }

        // Search by title or ISBN
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhere('isbn', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        // Price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $books = $query->paginate($request->get('per_page', 15));

        return response()->json($books);
    }

    /**
     * Get single book with details.
     */
    public function show($id)
    {
        $book = Book::with(['category', 'author', 'reviews'])->findOrFail($id);

        $bookData = $book->toArray();
        $bookData['average_rating'] = $book->average_rating;
        $bookData['review_count'] = $book->reviews->count();

        return response()->json(['book' => $bookData]);
    }

    /**
     * Create a new book (admin).
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books',
            'description' => 'nullable|string|max:5000',
            'price' => 'required|numeric|min:0',
            'cover_image_url' => 'nullable|url|max:500',
            'publication_date' => 'nullable|date',
            'pages' => 'nullable|integer|min:1',
            'language' => 'sometimes|string|max:50',
            'format' => 'sometimes|in:physical,ebook,both',
            'category_id' => 'nullable|exists:categories,id',
            'author_id' => 'nullable|exists:authors,id',
        ]);

        $book = Book::create($request->only([
            'title', 'isbn', 'description', 'price', 'cover_image_url',
            'publication_date', 'pages', 'language', 'format',
            'category_id', 'author_id',
        ]));

        $book->load(['category', 'author']);

        return response()->json([
            'message' => 'Book created',
            'book' => $book,
        ], 201);
    }

    /**
     * Update a book (admin).
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,' . $id,
            'description' => 'nullable|string|max:5000',
            'price' => 'sometimes|numeric|min:0',
            'cover_image_url' => 'nullable|url|max:500',
            'publication_date' => 'nullable|date',
            'pages' => 'nullable|integer|min:1',
            'language' => 'sometimes|string|max:50',
            'format' => 'sometimes|in:physical,ebook,both',
            'category_id' => 'nullable|exists:categories,id',
            'author_id' => 'nullable|exists:authors,id',
            'is_available' => 'sometimes|boolean',
        ]);

        $book->update($request->only([
            'title', 'isbn', 'description', 'price', 'cover_image_url',
            'publication_date', 'pages', 'language', 'format',
            'category_id', 'author_id', 'is_available',
        ]));

        $book->load(['category', 'author']);

        return response()->json([
            'message' => 'Book updated',
            'book' => $book,
        ]);
    }

    /**
     * Toggle book availability (inter-service call from Inventory).
     */
    public function updateAvailability(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'is_available' => 'required|boolean',
        ]);

        $book->update(['is_available' => $request->is_available]);

        return response()->json([
            'message' => 'Book availability updated',
            'book_id' => $book->id,
            'is_available' => $book->is_available,
        ]);
    }

    /**
     * Soft delete a book (admin).
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Book deleted']);
    }

    /**
     * Get reviews for a book.
     */
    public function getReviews($id)
    {
        $book = Book::findOrFail($id);
        $reviews = $book->reviews()->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($reviews);
    }

    /**
     * Submit a review for a book.
     */
    public function storeReview(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $review = $book->reviews()->create($request->only([
            'customer_name', 'customer_email', 'rating', 'comment',
        ]));

        return response()->json([
            'message' => 'Review submitted',
            'review' => $review,
        ], 201);
    }
}
