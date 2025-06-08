<?php

use Illuminate\Support\Facades\Route;
use Modules\BookCopy\Http\Controllers\BookCopyController;

Route::get('book-copy', [
    BookCopyController::class, 'index', 
])->name('bookCopy.index');

Route::get('book-copy/create', [
    BookCopyController::class, 'create',
])->name('bookCopy.create');

Route::post('book-copy', [
    BookCopyController::class, 'store',
])->name('bookCopy.store');

Route::get('book-copy/{id}/edit', [
    BookCopyController::class, 'edit',
])->name('bookCopy.edit');

Route::put('book-copy/{id}', [
    BookCopyController::class, 'update',
])->name('bookCopy.update');

Route::delete('book-copy/{id}', [
    BookCopyController::class, 'destroy',
])->name('bookCopy.destroy');
