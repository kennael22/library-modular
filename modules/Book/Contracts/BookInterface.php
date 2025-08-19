<?php

namespace Modules\Book\Contracts;

use Modules\Book\Models\Book;
use Illuminate\Database\Eloquent\Builder;

interface BookInterface
{
    /**
     * Create a new book
     *
     * @param array $data
     * @return Book
     */
    public function create(array $data): Book;

    /**
     * Edit a book
     *
     * @param int $id
     * @return Book|null
     */
    public function edit(int $id): Book|null;

    /**
     * Update an existing book
     *
     * @param array $data
     * @param Book $book
     * @return Book
     */
    public function update(array $data, Book $book): Book;

    /**
     * Show/retrieve books with optional search
     *
     * @param array $data
     * @return Builder
     */
    public function show(array $data = []): Builder;

    /**
     * Destroy/delete a book
     *
     * @param Book $book
     * @return bool
     */
    public function destroy(Book $book): bool;
}
