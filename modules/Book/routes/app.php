<?php

use Illuminate\Support\Facades\Route;
use Modules\Book\Http\Controllers\BookController;

Route::get('book', [
    BookController::class, 'index',
])->name('book.index');

Route::get('book/create', [
    BookController::class, 'create',
])->name('book.create');

Route::post('book', [
    BookController::class, 'store',
])->name('book.store');

Route::get('book/{id}/edit', [
    BookController::class, 'edit',
])->name('book.edit');

Route::post('book/{id}', [
    BookController::class, 'update',
])->name('book.update');

Route::delete('book/{id}', [
    BookController::class, 'destroy',
])->name('book.destroy');
