<?php

use Illuminate\Support\Facades\Route;
use Modules\Member\Http\Controllers\MemberController;

Route::get('member', [
    MemberController::class, 'index', 
])->name('member.index');

Route::get('member/create', [
    MemberController::class, 'create',
])->name('member.create');

Route::post('member', [
    MemberController::class, 'store',
])->name('member.store');

Route::get('member/{id}/edit', [
    MemberController::class, 'edit',
])->name('member.edit');

Route::put('member/{id}', [
    MemberController::class, 'update',
])->name('member.update');

Route::delete('member/{id}', [
    MemberController::class, 'destroy',
])->name('member.destroy');
