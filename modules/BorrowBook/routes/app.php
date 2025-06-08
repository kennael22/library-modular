<?php

use Illuminate\Support\Facades\Route;
use Modules\BorrowBook\Http\Controllers\BorrowBookController;

Route::get('borrow-book', [
    BorrowBookController::class, 'index', 
])->name('borrowBook.index');

Route::get('borrow-book/create', [
    BorrowBookController::class, 'create',
])->name('borrowBook.create');

Route::post('borrow-book', [
    BorrowBookController::class, 'store',
])->name('borrowBook.store');

Route::get('borrow-book/{id}/edit', [
    BorrowBookController::class, 'edit',
])->name('borrowBook.edit');

Route::put('borrow-book/{id}', [
    BorrowBookController::class, 'update',
])->name('borrowBook.update');

Route::delete('borrow-book/{id}', [
    BorrowBookController::class, 'destroy',
])->name('borrowBook.destroy');
