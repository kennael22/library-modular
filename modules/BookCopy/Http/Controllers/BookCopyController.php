<?php

namespace Modules\BookCopy\Http\Controllers;

use Modules\Support\Http\Controllers\BackendController;
use Modules\BookCopy\Http\Requests\BookCopyValidate;
use Modules\BookCopy\Models\BookCopy;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class BookCopyController extends BackendController
{
    public function index(): Response
    {
        $bookCopies = BookCopy::orderBy('name')
            ->search(request('searchContext'), request('searchTerm'))
            ->paginate(request('rowsPerPage', 10))
            ->withQueryString()
            ->through(fn ($bookCopy) => [
                    'id' => $bookCopy->id,
                    'name' => $bookCopy->name,
                    'created_at' => $bookCopy->created_at->format('d/m/Y H:i') . 'h'
            ]);

        return inertia('BookCopy/BookCopyIndex', [
            'bookCopies' => $bookCopies
        ]);
    }

    public function create(): Response
    {
        return inertia('BookCopy/BookCopyForm');
    }

    public function store(BookCopyValidate $request): RedirectResponse
    {
        BookCopy::create($request->validated());

        return redirect()->route('bookCopy.index')
            ->with('success', 'BookCopy created.');
    }

    public function edit(int $id): Response
    {
        $bookCopy = BookCopy::find($id);

        return inertia('BookCopy/BookCopyForm', [
            'bookCopy' => $bookCopy
        ]);
    }

    public function update(BookCopyValidate $request, int $id): RedirectResponse
    {
        $bookCopy = BookCopy::findOrFail($id);

        $bookCopy->update($request->validated());

        return redirect()->route('bookCopy.index')
            ->with('success', 'BookCopy updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        BookCopy::findOrFail($id)->delete();

        return redirect()->route('bookCopy.index')
            ->with('success', 'BookCopy deleted.');
    }
}
