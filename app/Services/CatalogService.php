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
     * Mark a book as unavailable in the Catalog service when stock runs out.
     *
     * @param int  $bookId
     * @param bool $isAvailable
     * @return bool
     */
    public function updateBookAvailability($bookId, $isAvailable)
    {
        try {
            $response = Http::timeout(10)
                ->patch("{$this->baseUrl}/books/{$bookId}/availability", [
                    'is_available' => $isAvailable,
                ]);

            if ($response->successful()) {
                Log::info("CatalogService: Book {$bookId} availability set to " . ($isAvailable ? 'true' : 'false'));
                return true;
            }

            Log::warning("CatalogService: Failed to update book {$bookId} availability", [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error("CatalogService: Exception updating book {$bookId} availability", [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get book details from the Catalog service.
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

            return null;
        } catch (\Exception $e) {
            Log::error("CatalogService: Exception getting book {$bookId}", [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
