<?php

use Illuminate\Support\Facades\Route;
use Modules\Author\Http\Controllers\AuthorController;

Route::get('author', [
    AuthorController::class, 'index', 
])->name('author.index');

Route::get('author/create', [
    AuthorController::class, 'create',
])->name('author.create');

Route::post('author', [
    AuthorController::class, 'store',
])->name('author.store');

Route::get('author/{id}/edit', [
    AuthorController::class, 'edit',
])->name('author.edit');

Route::put('author/{id}', [
    AuthorController::class, 'update',
])->name('author.update');

Route::delete('author/{id}', [
    AuthorController::class, 'destroy',
])->name('author.destroy');
