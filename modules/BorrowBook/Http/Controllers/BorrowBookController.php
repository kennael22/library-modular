<?php

namespace Modules\BorrowBook\Http\Controllers;

use Modules\Support\Http\Controllers\BackendController;
use Modules\BorrowBook\Http\Requests\BorrowBookValidate;
use Modules\BorrowBook\Models\BorrowBook;
use Modules\Member\Models\Member;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class BorrowBookController extends BackendController
{
    public function index(): Response
    {
        $borrowBooks = BorrowBook::orderBy('name')
            ->search(request('searchContext'), request('searchTerm'))
            ->paginate(request('rowsPerPage', 10))
            ->withQueryString()
            ->through(fn ($borrowBook) => [
                    'id' => $borrowBook->id,
                    'name' => $borrowBook->name,
                    'created_at' => $borrowBook->created_at->format('d/m/Y H:i') . 'h'
            ]);

        return inertia('BorrowBook/BorrowBookIndex', [
            'borrowBooks' => $borrowBooks
        ]);
    }

    public function create(): Response
    {
        $members = Member::get();

        return inertia('BorrowBook/BorrowBookForm', [
            'members' => $members,
        ]);
    }

    public function store(BorrowBookValidate $request): RedirectResponse
    {
        BorrowBook::create($request->validated());

        return redirect()->route('borrowBook.index')
            ->with('success', 'BorrowBook created.');
    }

    public function edit(int $id): Response
    {
        $borrowBook = BorrowBook::find($id);

        return inertia('BorrowBook/BorrowBookForm', [
            'borrowBook' => $borrowBook
        ]);
    }

    public function update(BorrowBookValidate $request, int $id): RedirectResponse
    {
        $borrowBook = BorrowBook::findOrFail($id);

        $borrowBook->update($request->validated());

        return redirect()->route('borrowBook.index')
            ->with('success', 'BorrowBook updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        BorrowBook::findOrFail($id)->delete();

        return redirect()->route('borrowBook.index')
            ->with('success', 'BorrowBook deleted.');
    }
}
