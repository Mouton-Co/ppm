<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Design\UploadFilesController;

/*
|--------------------------------------------------------------------------
| Designer routes
|--------------------------------------------------------------------------
*/

Route::middleware('designer')->group(function () {
    Route::get('/designer', [DashboardController::class, 'designer'])->name('dashboard.designer');
    Route::post('/upload', [UploadFilesController::class, 'uploadFile'])->name('designer.upload');
});
