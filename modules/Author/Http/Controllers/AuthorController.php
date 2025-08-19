<?php

namespace Modules\Author\Http\Controllers;

use Modules\Support\Http\Controllers\BackendController;
use Modules\Author\Http\Requests\AuthorValidate;
use Modules\Author\Models\Author;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class AuthorController extends BackendController
{
    public function index(): Response
    {
        $authors = Author::orderBy('last_name')
            ->search(request('searchContext'), request('searchTerm'))
            ->paginate(request('rowsPerPage', 10))
            ->withQueryString()
            ->through(fn ($author) => [
                    'id' => $author->id,
                    'first_name' => $author->first_name,
                    'middle_name' => $author->middle_name,
                    'last_name' => $author->last_name,
                    'suffix_name' => $author->suffix_name,
                    'created_at' => $author->created_at->format('d/m/Y H:i') . 'h'
            ]);

        return inertia('Author/AuthorIndex', [
            'authors' => $authors
        ]);
    }

    public function create(): Response
    {
        return inertia('Author/AuthorForm');
    }

    public function store(AuthorValidate $request): RedirectResponse
    {
        Author::create($request->validated());

        return redirect()->route('author.index')
            ->with('success', 'Author created.');
    }

    public function edit(int $id): Response
    {
        $author = Author::find($id);

        return inertia('Author/AuthorForm', [
            'author' => $author
        ]);
    }

    public function update(AuthorValidate $request, int $id): RedirectResponse
    {
        $author = Author::findOrFail($id);

        $author->update($request->validated());

        return redirect()->route('author.index')
            ->with('success', 'Author updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        Author::findOrFail($id)->delete();

        return redirect()->route('author.index')
            ->with('success', 'Author deleted.');
    }

    public function getAuthors()
    {
        return Author::select('id', 'first_name', 'middle_name', 'last_name', 'suffix_name')
        ->with('books:id,title')
        ->when(request('searchColumn'), function ($query) {
            $searchColumn = request('searchColumn');
            $searchTerm = request('searchTerm');
            $query->search($searchColumn, $searchTerm);
        })
        ->limit(10)
        ->get()
        ->map(function ($author) {
            return [
                'label' => $author->first_name . ' ' . $author->middle_name . ' ' . $author->last_name . ' ' . $author->suffix_name,
                'value' => $author->id,
                'id' => $author->id,
                // map books
                'books' => $author->books()->get()->map(function ($book) {
                    return [
                        'label' => $book->title,
                        'value' => $book->id,
                        'author_id' => $book->author_id,
                        'id' => $book->id
                    ];
                })
            ];
        });
    }
}
