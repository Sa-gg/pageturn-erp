<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Categories
        $categories = [
            ['name' => 'Fiction', 'description' => 'Novels, short stories, and literary fiction', 'slug' => 'fiction', 'is_active' => true],
            ['name' => 'Non-Fiction', 'description' => 'Biographies, history, science, and self-help', 'slug' => 'non-fiction', 'is_active' => true],
            ['name' => 'Science & Technology', 'description' => 'Computer science, engineering, and natural sciences', 'slug' => 'science-technology', 'is_active' => true],
            ['name' => 'Children & Young Adult', 'description' => 'Books for kids and teenagers', 'slug' => 'children-young-adult', 'is_active' => true],
            ['name' => 'Academic & Textbooks', 'description' => 'University textbooks and reference materials', 'slug' => 'academic-textbooks', 'is_active' => true],
            ['name' => 'Comics & Manga', 'description' => 'Graphic novels, comics, and manga series', 'slug' => 'comics-manga', 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Authors
        $authors = [
            ['name' => 'J.K. Rowling', 'bio' => 'British author best known for the Harry Potter series.'],
            ['name' => 'George Orwell', 'bio' => 'English novelist and essayist, author of 1984 and Animal Farm.'],
            ['name' => 'Robert C. Martin', 'bio' => 'Software engineer and author known as Uncle Bob, author of Clean Code.'],
            ['name' => 'Yuval Noah Harari', 'bio' => 'Israeli historian and professor, author of Sapiens.'],
            ['name' => 'Dr. Seuss', 'bio' => 'American author and illustrator of beloved children\'s books.'],
            ['name' => 'Eiichiro Oda', 'bio' => 'Japanese manga artist, creator of One Piece.'],
        ];

        $authorModels = [];
        foreach ($authors as $auth) {
            $authorModels[] = \App\Models\Author::updateOrCreate(['name' => $auth['name']], $auth);
        }

        // Books
        $books = [
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'isbn' => '978-0747532699',
                'description' => 'The first book in the Harry Potter series, following a young wizard\'s journey.',
                'price' => 499.00,
                'pages' => 223,
                'publication_date' => '1997-06-26',
                'language' => 'English',
                'format' => 'physical',
                'category_id' => 1, // Fiction
                'author_id' => 1,   // Rowling
                'is_available' => true,
            ],
            [
                'title' => '1984',
                'isbn' => '978-0451524935',
                'description' => 'A dystopian novel set in a totalitarian society ruled by Big Brother.',
                'price' => 350.00,
                'pages' => 328,
                'publication_date' => '1949-06-08',
                'language' => 'English',
                'format' => 'both',
                'category_id' => 1,
                'author_id' => 2,
                'is_available' => true,
            ],
            [
                'title' => 'Clean Code',
                'isbn' => '978-0132350884',
                'description' => 'A handbook of agile software craftsmanship.',
                'price' => 1200.00,
                'pages' => 464,
                'publication_date' => '2008-08-01',
                'language' => 'English',
                'format' => 'physical',
                'category_id' => 3, // Science & Tech
                'author_id' => 3,
                'is_available' => true,
            ],
            [
                'title' => 'Sapiens: A Brief History of Humankind',
                'isbn' => '978-0062316097',
                'description' => 'A groundbreaking narrative of humanity\'s creation and evolution.',
                'price' => 750.00,
                'pages' => 443,
                'publication_date' => '2011-01-01',
                'language' => 'English',
                'format' => 'both',
                'category_id' => 2, // Non-Fiction
                'author_id' => 4,
                'is_available' => true,
            ],
            [
                'title' => 'The Cat in the Hat',
                'isbn' => '978-0394800011',
                'description' => 'A beloved children\'s book about a mischievous cat.',
                'price' => 299.00,
                'pages' => 61,
                'publication_date' => '1957-03-12',
                'language' => 'English',
                'format' => 'physical',
                'category_id' => 4, // Children
                'author_id' => 5,
                'is_available' => true,
            ],
            [
                'title' => 'One Piece Vol. 1',
                'isbn' => '978-1569319017',
                'description' => 'The beginning of Monkey D. Luffy\'s adventure to become King of the Pirates.',
                'price' => 399.00,
                'pages' => 216,
                'publication_date' => '1997-12-24',
                'language' => 'English',
                'format' => 'physical',
                'category_id' => 6, // Comics & Manga
                'author_id' => 6,
                'is_available' => true,
            ],
            [
                'title' => 'Animal Farm',
                'isbn' => '978-0451526342',
                'description' => 'A satirical allegorical novella about a group of farm animals.',
                'price' => 280.00,
                'pages' => 141,
                'publication_date' => '1945-08-17',
                'language' => 'English',
                'format' => 'both',
                'category_id' => 1,
                'author_id' => 2,
                'is_available' => true,
            ],
            [
                'title' => 'The Clean Coder',
                'isbn' => '978-0137081073',
                'description' => 'A code of conduct for professional programmers.',
                'price' => 1100.00,
                'pages' => 256,
                'publication_date' => '2011-05-23',
                'language' => 'English',
                'format' => 'physical',
                'category_id' => 3,
                'author_id' => 3,
                'is_available' => true,
            ],
        ];

        foreach ($books as $book) {
            \App\Models\Book::updateOrCreate(['isbn' => $book['isbn']], $book);
        }
    }
}
