<?php

namespace Modules\Book\Tests\Unit;

use Tests\TestCase;
use Modules\Book\Services\BookService;
use Modules\Book\Models\Book;
use Modules\Author\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;

/**
 * Why is my database data deleted when I run the test?
 *
 * This is because the test class uses the RefreshDatabase trait:
 *
 *     use RefreshDatabase;
 *
 * The RefreshDatabase trait will automatically migrate and refresh (rollback and re-run) the database before each test.
 * This ensures a clean state for every test, but it also means any data in your database will be wiped out and replaced with a fresh schema.
 *
 * If you are using your main (production or development) database for testing, all data will be lost.
 * To avoid this, always use a dedicated test database (e.g., sqlite in-memory or a separate test database).
 *
 * See: https://laravel.com/docs/10.x/database-testing#resetting-the-database-after-each-test
 */

class BookServiceTest extends TestCase
{
    use RefreshDatabase;

    private BookService $bookService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookService = new BookService();
    }

    #[Test]
    public function it_can_create_book()
    {
        // Arrange
        $author = Author::factory()->create();
        $bookData = [
            'title' => 'Test Book',
            'author_id' => $author->id,
            'genre' => 'Fiction',
            'quantity' => 5,
            'access_book_number' => 'TEST001'
        ];

        // Act
        $book = $this->bookService->create($bookData);

        // Assert
        $this->assertInstanceOf(Book::class, $book);
        $this->assertEquals('Test Book', $book->title);
        $this->assertEquals($author->id, $book->author_id);
        $this->assertEquals(5, $book->quantity);
        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'author_id' => $author->id
        ]);
    }

    #[Test]
    public function it_throws_exception_when_creating_book_without_title()
    {
        // Arrange
        $author = Author::factory()->create();
        $bookData = [
            'author_id' => $author->id,
            'genre' => 'Fiction',
            'quantity' => 5
        ];

        // Act & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Book title is required');
        $this->bookService->create($bookData);
    }

    #[Test]
    public function it_throws_exception_when_creating_book_without_author()
    {
        // Arrange
        $bookData = [
            'title' => 'Test Book',
            'genre' => 'Fiction',
            'quantity' => 5
        ];

        // Act & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Author is required');
        $this->bookService->create($bookData);
    }

    #[Test]
    public function it_throws_exception_when_creating_book_with_invalid_quantity()
    {
        // Arrange
        $author = Author::factory()->create();
        $bookData = [
            'title' => 'Test Book',
            'author_id' => $author->id,
            'genre' => 'Fiction',
            'quantity' => 0
        ];

        // Act & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity must be greater than 0');
        $this->bookService->create($bookData);
    }

    #[Test]
    public function it_can_update_book()
    {
        // Arrange
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'quantity' => 10
        ]);

        $updateData = [
            'title' => 'Updated Book Title',
            'quantity' => 15
        ];

        // Act
        $updatedBook = $this->bookService->update($updateData, $book);

        // Assert
        $this->assertEquals('Updated Book Title', $updatedBook->title);
        $this->assertEquals(15, $updatedBook->quantity);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Book Title',
            'quantity' => 15
        ]);
    }

    #[Test]
    public function it_cannot_update_book_with_quantity_less_than_borrowed_copies()
    {
        // Arrange
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'quantity' => 10
        ]);

        // Create some borrowed book copies
        $book->bookCopies()->createMany([
            ['is_available' => false], // borrowed
            ['is_available' => false], // borrowed
            ['is_available' => true],  // available
        ]);

        $updateData = [
            'quantity' => 1 // Less than borrowed copies (2)
        ];

        // Act & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity cannot be less than the number of borrowed copies (2)');
        $this->bookService->update($updateData, $book);
    }

    #[Test]
    public function it_can_destroy_book()
    {
        // Arrange
        $book = Book::factory()->create();

        // Act
        $result = $this->bookService->destroy($book);

        // Assert
        $this->assertTrue($result);
        $this->assertSoftDeleted('books', ['id' => $book->id]);
    }

    #[Test]
    public function it_can_show_all_books()
    {
        // Arrange
        $author = Author::factory()->create();
        Book::factory()->count(3)->create(['author_id' => $author->id]);

        // Act
        $query = $this->bookService->show();
        $books = $query->get();

        // Assert
        $this->assertCount(3, $books);
    }

    #[Test]
    public function it_can_show_books_with_search_by_title()
    {
        // Arrange
        $author = Author::factory()->create();
        Book::factory()->create([
            'title' => 'PHP Programming',
            'author_id' => $author->id
        ]);
        Book::factory()->create([
            'title' => 'JavaScript Basics',
            'author_id' => $author->id
        ]);

        $searchData = [
            'searchTerm' => 'PHP',
            'searchContext' => 'title'
        ];

        // Act
        $query = $this->bookService->show($searchData);
        $books = $query->get();

        // Assert
        $this->assertCount(1, $books);
        $this->assertEquals('PHP Programming', $books->first()->title);
    }

    #[Test]
    public function it_can_show_books_with_search_by_author()
    {
        // Arrange
        $author = Author::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);
        Book::factory()->create([
            'title' => 'Test Book',
            'author_id' => $author->id
        ]);

        $searchData = [
            'searchTerm' => 'Jane',
            'searchContext' => 'author'
        ];

        // Act
        $query = $this->bookService->show($searchData);
        $books = $query->get();

        // Assert
        $this->assertCount(1, $books);
        $this->assertEquals($author->id, $books->first()->author_id);
    }

    #[Test]
    public function it_can_show_books_with_search_by_genre()
    {
        // Arrange
        $author = Author::factory()->create();
        Book::factory()->create([
            'title' => 'Test Book',
            'author_id' => $author->id,
            'genre' => 'Programming'
        ]);

        $searchData = [
            'searchTerm' => 'Programming',
            'searchContext' => 'genre'
        ];

        // Act
        $query = $this->bookService->show($searchData);
        $books = $query->get();

        // Assert
        $this->assertCount(1, $books);
        $this->assertEquals('Programming', $books->first()->genre);
    }

    #[Test]
    public function it_can_show_books_with_search_by_access_number()
    {
        // Arrange
        $author = Author::factory()->create();
        Book::factory()->create([
            'title' => 'Test Book',
            'author_id' => $author->id,
            'access_book_number' => 'ACC001'
        ]);

        $searchData = [
            'searchTerm' => 'ACC001',
            'searchContext' => 'access_number'
        ];

        // Act
        $query = $this->bookService->show($searchData);
        $books = $query->get();

        // Assert
        $this->assertCount(1, $books);
        $this->assertEquals('ACC001', $books->first()->access_book_number);
    }

    #[Test]
    public function it_can_show_books_with_default_search()
    {
        // Arrange
        $author = Author::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        Book::factory()->create([
            'title' => 'PHP Programming',
            'author_id' => $author->id,
            'genre' => 'Programming'
        ]);
        Book::factory()->create([
            'title' => 'JavaScript Basics',
            'author_id' => $author->id,
            'genre' => 'Programming'
        ]);

        $searchData = [
            'searchTerm' => 'PHP'
        ];

        // Act
        $query = $this->bookService->show($searchData);
        $books = $query->get();

        // Assert
        $this->assertCount(1, $books);
        $this->assertEquals('PHP Programming', $books->first()->title);
    }

    #[Test]
    public function it_returns_empty_result_when_search_term_is_empty()
    {
        // Arrange
        $author = Author::factory()->create();
        Book::factory()->create([
            'title' => 'Test Book',
            'author_id' => $author->id
        ]);

        $searchData = [
            'searchTerm' => '',
            'searchContext' => 'title'
        ];

        // Act
        $query = $this->bookService->show($searchData);
        $books = $query->get();

        // Assert
        $this->assertCount(1, $books); // Should return all books when search term is empty
    }
}