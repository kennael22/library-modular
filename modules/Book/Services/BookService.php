<?php

namespace Modules\Book\Services;

use Modules\Book\Contracts\BookInterface;
use Modules\Book\Models\Book;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class BookService implements BookInterface
{
    /**
     * Create a new book
     *
     * @param array $data
     * @return Book
     * @throws \Exception
     */
    public function create(array $data): Book
    {
        $this->validateCreateData($data);

        DB::beginTransaction();

        try {
            $book = Book::create($data);
            DB::commit();

            Log::info('Book created successfully', ['book_id' => $book->id]);

            return $book;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Book creation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            throw $e;
        }
    }

    /**
     * Edit a book
     *
     * @param int $id
     * @return Book|null
     */
    public function edit(int $id): Book|null
    {
        return Book::find($id);
    }

    /**
     * Update an existing book
     *
     * @param array $data
     * @param Book $book
     * @return Book
     * @throws \Exception
     */
    public function update(array $data, Book $book): Book
    {
        $this->validateUpdateData($data, $book);

        DB::beginTransaction();

        try {
            $book->update($data);
            DB::commit();

            Log::info('Book updated successfully', [
                'book_id' => $book->id,
                'updated_fields' => array_keys($data)
            ]);

            return $book;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Book update failed', [
                'error' => $e->getMessage(),
                'book_id' => $book->id,
                'data' => $data
            ]);

            throw $e;
        }
    }

    /**
     * Show/retrieve books with optional search
     *
     * @param array $data
     * @return Builder
     */
    public function show(array $data = []): Builder
    {
        $query = Book::query();

        // Apply search if provided
        if (!empty($data['searchTerm']) || !empty($data['searchContext'])) {
            $query = $this->applySearch($query, $data);
        }

        return $query;
    }

    /**
     * Destroy/delete a book
     *
     * @param Book $book
     * @return bool
     * @throws \Exception
     */
    public function destroy(Book $book): bool
    {
        try {
            // Delete cover image if exists
            if ($book->cover_image) {
                $this->deleteCoverImage($book->cover_image);
            }

            $book->delete();

            Log::info('Book destroyed successfully', ['book_id' => $book->id]);

            return true;
        } catch (\Exception $e) {
            Log::error('Book destruction failed', [
                'error' => $e->getMessage(),
                'book_id' => $book->id
            ]);

            throw $e;
        }
    }

    /**
     * Validate data for book creation
     *
     * @param array $data
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateCreateData(array $data): void
    {
        if (empty($data['title'])) {
            throw new InvalidArgumentException('Book title is required');
        }

        if (empty($data['author_id'])) {
            throw new InvalidArgumentException('Author is required');
        }

        if (!isset($data['quantity']) || $data['quantity'] <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than 0');
        }
    }

    /**
     * Validate data for book update
     *
     * @param array $data
     * @param Book $book
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateUpdateData(array $data, Book $book): void
    {
        // Check if quantity is being updated
        if (isset($data['quantity'])) {
            $newQty = (int) $data['quantity'];
            $borrowedCount = $book->bookCopies()->where('is_available', false)->count();

            if ($newQty < $borrowedCount) {
                throw new InvalidArgumentException(
                    "Quantity cannot be less than the number of borrowed copies ({$borrowedCount})"
                );
            }
        }
    }

    /**
     * Apply search filters to the query
     *
     * @param Builder $query
     * @param array $data
     * @return Builder
     */
    private function applySearch(Builder $query, array $data): Builder
    {
        $searchTerm = $data['searchTerm'] ?? '';
        $searchContext = $data['searchContext'] ?? '';

        if (empty($searchTerm)) {
            return $query;
        }

        switch ($searchContext) {
            case 'title':
                return $query->where('title', 'like', '%' . $searchTerm . '%');

            case 'author':
                return $query->whereHas('author', function ($q) use ($searchTerm) {
                    $q->where('first_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                });

            case 'genre':
                return $query->where('genre', 'like', '%' . $searchTerm . '%');

            case 'access_number':
                return $query->where('access_book_number', 'like', '%' . $searchTerm . '%');

            default:
                return $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('genre', 'like', '%' . $searchTerm . '%')
                      ->orWhere('access_book_number', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('author', function ($authorQuery) use ($searchTerm) {
                          $authorQuery->where('first_name', 'like', '%' . $searchTerm . '%')
                                     ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                      });
                });
        }
    }

    /**
     * Delete the cover image file
     *
     * @param string $imageName
     * @return void
     */
    private function deleteCoverImage(string $imageName): void
    {
        $filePath = storage_path('app/public/book/' . $imageName);

        if (file_exists($filePath)) {
            unlink($filePath);
            Log::info('Cover image deleted', ['image' => $imageName]);
        }
    }
}
