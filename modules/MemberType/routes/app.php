<?php

use Illuminate\Support\Facades\Route;
use Modules\MemberType\Http\Controllers\MemberTypeController;

Route::get('member-type', [
    MemberTypeController::class, 'index', 
])->name('memberType.index');

Route::get('member-type/create', [
    MemberTypeController::class, 'create',
])->name('memberType.create');

Route::post('member-type', [
    MemberTypeController::class, 'store',
])->name('memberType.store');

Route::get('member-type/{id}/edit', [
    MemberTypeController::class, 'edit',
])->name('memberType.edit');

Route::put('member-type/{id}', [
    MemberTypeController::class, 'update',
])->name('memberType.update');

Route::delete('member-type/{id}', [
    MemberTypeController::class, 'destroy',
])->name('memberType.destroy');
