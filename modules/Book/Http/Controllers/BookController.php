<?php

namespace Modules\Book\Http\Controllers;

use Modules\Support\Http\Controllers\BackendController;
use Modules\Book\Http\Requests\BookValidate;
use Modules\Book\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Modules\Author\Models\Author;
use Modules\Support\Traits\UploadFile;
use Modules\Book\Services\BookService;

class BookController extends BackendController
{
    use UploadFile;

    private BookService $bookService;

    /**
     * Create a new BookController instance.
     *
     * @param BookService $bookService
     */
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Display a listing of the books.
     *
     * @return Response
     */
    public function index(): Response
    {
        $searchData = [
            'searchContext' => request('searchContext'),
            'searchTerm' => request('searchTerm')
        ];

        $books = $this->bookService->show($searchData)
            ->orderBy('title')
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

    /**
     * Show the form for creating a new book.
     *
     * @return Response
     */
    public function create(): Response
    {
        $authors = $this->getAuthors();
        return inertia('Book/BookForm', [
            'authors' => $authors
        ]);
    }

    /**
     * Store a newly created book in storage.
     *
     * @param BookValidate $request
     * @return RedirectResponse
     */
    public function store(BookValidate $request): RedirectResponse
    {
        $bookData = $request->validated();

        if ($request->hasFile('cover_image')) {
            $bookData = array_merge($bookData, $this->uploadFile('cover_image', 'book', 'originalUUID', 'public'));
        }

        try {
            $this->bookService->create($bookData);
        } catch (\Exception $e) {
            return redirect()->route('book.index')
                ->with('error', 'Book creation failed.');
        }

        return redirect()->route('book.index')
            ->with('success', 'Book created.');
    }

    /**
     * Show the form for editing the specified book.
     *
     * This method should be included in the BookInterface as:
     * public function edit(int $id): Book|null;
     */
    public function edit(int $id): Response
    {
        $authors = $this->getAuthors();
        // Use the BookService (which implements BookInterface) to fetch the book
        $book = $this->bookService->edit($id);

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

    /**
     * Update the specified book in storage.
     *
     * @param BookValidate $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(BookValidate $request, int $id): RedirectResponse
    {
        $book = Book::findOrFail($id);
        $bookData = $request->validated();

        if ($request->hasFile('cover_image')) {
            $bookData = array_merge($bookData, $this->uploadFile('cover_image', 'book', 'originalUUID', 'public'));
        } else {
            $bookData['cover_image'] = null;
            // unset($bookData['cover_image']);
        }

        try {
            $this->bookService->update($bookData, $book);
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('book.index')
                ->with('error', 'Book update failed.');
        }

        return redirect()->route('book.index')
            ->with('success', 'Book updated.');
    }

    /**
     * Remove the specified book from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $book = Book::findOrFail($id);

        try {
            $this->bookService->destroy($book);
        } catch (\Exception $e) {
            return redirect()->route('book.index')
                ->with('error', 'Book deletion failed.');
        }

        return redirect()->route('book.index')
            ->with('success', 'Book deleted.');
    }

    /**
     * Get all authors.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAuthors()
    {
        return Author::select('id', 'first_name', 'middle_name', 'last_name', 'suffix_name')->get();
    }
}
