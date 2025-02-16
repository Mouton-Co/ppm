<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User management routes
|--------------------------------------------------------------------------
*/

Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');

Route::middleware(['admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::post('/user/restore/{id}', [UserController::class, 'restore'])->name('user.restore');
    Route::post('/user/trash/{id}', [UserController::class, 'trash'])->name('user.trash');
    Route::post('/users/export', [UserController::class, 'export'])->name('user.export');
});
