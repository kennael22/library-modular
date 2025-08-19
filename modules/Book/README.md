# Book Module - BookInterface Implementation

This module implements a clean service layer using the BookInterface to handle all book operations in a maintainable and extensible way.

## Overview

The BookInterface provides a clean contract for all book operations, following the Interface Segregation Principle and making the code more testable and maintainable. The BookService implements this interface with comprehensive business logic, validation, and error handling.

## Architecture

### Core Components

1. **BookInterface** (`Contracts/BookInterface.php`)
   - Defines the contract for all book operations
   - Contains four main methods: `create`, `update`, `show`, `destroy`
   - Ensures consistency and provides a clear API

2. **BookService** (`Services/BookService.php`)
   - Implements the BookInterface
   - Contains all business logic for book operations
   - Handles validation, transactions, logging, and error handling

## Methods

### create(array $data): Book
- **Purpose**: Creates a new book
- **Features**:
  - Database transaction management
  - Validation of required fields (title, author_id, quantity)
  - Logging of operations
  - Error handling with rollback

### update(array $data, Book $book): Book
- **Purpose**: Updates an existing book
- **Features**:
  - Quantity validation (cannot be less than borrowed copies)
  - Database transaction management
  - Logging of operations
  - Error handling with rollback

### show(array $data = []): Builder
- **Purpose**: Retrieves books with optional search functionality
- **Search Types**:
  - `title`: Search by book title
  - `author`: Search by author name
  - `genre`: Search by genre
  - `access_number`: Search by access book number
  - `default`: Search across multiple fields

### destroy(Book $book): bool
- **Purpose**: Destroys/deletes a book
- **Features**:
  - Automatic cleanup of cover images
  - Soft delete support
  - Logging of operations
  - Error handling

## Usage

### In Controllers

```php
class BookController extends BackendController
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function store(BookValidate $request): RedirectResponse
    {
        $bookData = $request->validated();

        try {
            $this->bookService->create($bookData);
            return redirect()->route('book.index')->with('success', 'Book created.');
        } catch (\Exception $e) {
            return redirect()->route('book.index')->with('error', 'Book creation failed.');
        }
    }

    public function update(BookValidate $request, int $id): RedirectResponse
    {
        $book = Book::findOrFail($id);
        $bookData = $request->validated();

        try {
            $this->bookService->update($bookData, $book);
            return redirect()->route('book.index')->with('success', 'Book updated.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('book.index')->with('error', 'Book update failed.');
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $book = Book::findOrFail($id);

        try {
            $this->bookService->destroy($book);
            return redirect()->route('book.index')->with('success', 'Book deleted.');
        } catch (\Exception $e) {
            return redirect()->route('book.index')->with('error', 'Book deletion failed.');
        }
    }
}
```

### Direct Service Usage

```php
// Create a book
$book = $this->bookService->create($bookData);

// Update a book
$updatedBook = $this->bookService->update($bookData, $book);

// Destroy a book
$destroyed = $this->bookService->destroy($book);

// Show all books
$books = $this->bookService->show()->get();

// Show books with search
$books = $this->bookService->show([
    'searchTerm' => 'PHP',
    'searchContext' => 'title'
])->get();
```

## Benefits

1. **Clean Interface**: Simple and intuitive API with four main methods
2. **Separation of Concerns**: Business logic is separated from controllers
3. **Testability**: Easy to mock and test the service layer
4. **Maintainability**: All book operations are centralized in one service
5. **Consistency**: All operations follow the same patterns and error handling
6. **Extensibility**: Easy to extend with new methods or create alternative implementations

## Error Handling

All methods include proper error handling:
- Database transactions with rollback on failure
- Logging of operations and errors
- Appropriate exception throwing
- Validation before execution

## Logging

The service automatically logs:
- Successful operations with relevant details
- Failed operations with error information
- Important business logic decisions

This provides better debugging and monitoring capabilities.