<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| File routes
|--------------------------------------------------------------------------
*/
Route::prefix('files')->group(function () {
    Route::get('/download/file/{id}', [FileController::class, 'download'])->name('file.download');
    Route::get('/download/zip/{id}', [FileController::class, 'downloadZip'])->name('zip.download');
    Route::get('/{id}', [FileController::class, 'open'])->name('file.open');
});
