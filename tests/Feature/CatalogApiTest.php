<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogApiTest extends TestCase
{
    use RefreshDatabase;

    protected $category;
    protected $author;
    protected $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Fiction',
            'slug' => 'fiction',
            'description' => 'Fiction books',
        ]);

        $this->author = Author::create([
            'name' => 'John Doe',
            'biography' => 'Writer of stuff',
        ]);

        $this->book = Book::create([
            'title' => 'Sample Book',
            'isbn' => '1234567890123',
            'description' => 'A wonderful book',
            'price' => 29.99,
            'cover_image_url' => 'https://via.placeholder.com/150',
            'publication_date' => '2023-01-01',
            'pages' => 350,
            'language' => 'English',
            'format' => 'Paperback',
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_available' => true,
        ]);
    }

    public function test_can_list_categories()
    {
        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Fiction',
            ]);
    }

    public function test_can_list_authors()
    {
        $response = $this->getJson('/api/authors');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'John Doe',
            ]);
    }

    public function test_can_list_books_with_filters()
    {
        $response = $this->getJson('/api/books?search=Sample');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Sample Book',
            ]);
    }

    public function test_can_show_book()
    {
        $response = $this->getJson('/api/books/' . $this->book->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Sample Book',
                'isbn' => '1234567890123',
            ]);
    }

    public function test_can_add_book_review()
    {
        $response = $this->postJson('/api/books/' . $this->book->id . '/reviews', [
            'reviewer_name' => 'Jane reviewer',
            'rating' => 5,
            'comment' => 'Excellent book!',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('book_reviews', [
            'book_id' => $this->book->id,
            'rating' => 5,
        ]);
    }

    public function test_can_update_availability_internal()
    {
        $response = $this->patchJson('/api/books/' . $this->book->id . '/availability', [
            'is_available' => false,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', [
            'id' => $this->book->id,
            'is_available' => false,
        ]);
    }

    public function test_admin_can_create_book()
    {
        $response = $this->postJson('/api/admin/books', [
            'title' => 'New Admin Book',
            'isbn' => '9876543210987',
            'description' => 'Created by admin',
            'price' => 19.99,
            'category_id' => $this->category->id,
            'author_id' => $this->author->id,
            'is_available' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('books', [
            'title' => 'New Admin Book',
            'isbn' => '9876543210987',
        ]);
    }
}
