<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CatalogService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.catalog.base_url');
    }

    /**
     * Get book details from the Catalog service.
     * Used to validate book existence and get current price.
     *
     * @param int $bookId
     * @return array|null
     */
    public function getBook($bookId)
    {
        try {
            $response = Http::timeout(10)
                ->get("{$this->baseUrl}/books/{$bookId}");

            if ($response->successful()) {
                $data = $response->json();
                return $data['book'] ?? $data;
            }

            Log::warning("CatalogService: Failed to get book {$bookId}", [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error("CatalogService: Exception getting book {$bookId}", [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Validate a book exists and is available for purchase.
     *
     * @param int $bookId
     * @return array ['valid' => bool, 'book' => array|null, 'error' => string|null]
     */
    public function validateBook($bookId)
    {
        $book = $this->getBook($bookId);

        if (!$book) {
            return [
                'valid' => false,
                'book'  => null,
                'error' => "Book #{$bookId} not found in catalog.",
            ];
        }

        if (isset($book['is_available']) && !$book['is_available']) {
            return [
                'valid' => false,
                'book'  => $book,
                'error' => "Book '{$book['title']}' is currently unavailable.",
            ];
        }

        return [
            'valid' => true,
            'book'  => $book,
            'error' => null,
        ];
    }
}
