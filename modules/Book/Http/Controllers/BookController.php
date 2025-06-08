<?php

namespace Modules\Book\Http\Controllers;

use Modules\Support\Http\Controllers\BackendController;
use Modules\Book\Http\Requests\BookValidate;
use Modules\Book\Models\Book;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Modules\Author\Models\Author;
use Modules\Support\Traits\UploadFile;

class BookController extends BackendController
{
    use UploadFile;
    public function index(): Response
    {
        $books = Book::orderBy('title')
            ->search(request('searchContext'), request('searchTerm'))
            ->paginate(request('rowsPerPage', 10))
            ->withQueryString()
            ->through(fn ($book) => [
                    'id' => $book->id,
                    'cover_image' => $book->cover_image ? asset("storage/book/{$book->cover_image}") : null,
                    'title' => $book->title,
                    'author' => $book->author?->first_name . ' ' . $book->author?->middle_name . ' ' . $book->author?->last_name . ' ' . $book->author?->suffix_name,
                    'author_id' => $book->author?->id,
                    'genre' => $book->genre,
                    'access_book_number' => $book->access_book_number,
                    'created_at' => $book->created_at->format('d/m/Y H:i') . 'h'
            ]);

        return inertia('Book/BookIndex', [
            'books' => $books
        ]);
    }

    public function create(): Response
    {
        $authors = $this->getAuthors();
        return inertia('Book/BookForm', [
            'authors' => $authors
        ]);
    }

    public function store(BookValidate $request): RedirectResponse
    {
        $bookData = $request->validated();

        if ($request->hasFile('cover_image')) {
            $bookData = array_merge($bookData, $this->uploadFile('cover_image', 'book', 'originalUUID', 'public'));
        }

        Book::create($bookData);

        return redirect()->route('book.index')
            ->with('success', 'Book created.');
    }

    public function edit(int $id): Response
    {
        $authors = $this->getAuthors();
        $book = Book::find($id);

        if ($book) {
            // map authors
            $book->author_id = [
                'label' => $book->author?->first_name . ' ' . $book->author?->middle_name . ' ' . $book->author?->last_name . ' ' . $book->author?->suffix_name,
                'value' => $book->author?->id
            ];

            $book->cover_image = $book->cover_image ? asset("storage/book/{$book->cover_image}") : null;
        }
        return inertia('Book/BookForm', [
            'book' => $book,
            'authors' => $authors
        ]);
    }

    public function update(BookValidate $request, int $id): RedirectResponse
    {
        $book = Book::findOrFail($id);
        $bookData = $request->validated();
        if ($request->hasFile('cover_image')) {
            info('hasFile');
            $bookData = array_merge($bookData, $this->uploadFile('cover_image', 'book', 'originalUUID', 'public'));
        // } elseif ($request->input('remove_previous_image')) {
        //     info('remove_previous_image');
        //     $bookData['cover_image'] = null;
        } else {
            // info('else');
            $bookData['cover_image'] = null;
            // unset($bookData['cover_image']);
        }

        if ($book) {
            $book->update($bookData);
        }

        return redirect()->route('book.index')
            ->with('success', 'Book updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $book = Book::findOrFail($id);

        if ($book->cover_image) {
            $this->deleteFile($book->cover_image, 'book');
        }

        $book->delete();

        return redirect()->route('book.index')
            ->with('success', 'Book deleted.');
    }

    private function getAuthors()
    {
        return Author::select('id', 'first_name', 'middle_name', 'last_name', 'suffix_name')->get();
    }
}
