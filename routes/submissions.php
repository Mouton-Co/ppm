<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Design\SubmissionController;
use App\Http\Controllers\Design\UploadFilesController;
use App\Http\Controllers\FileController;

/*
|--------------------------------------------------------------------------
| File submission routes
|--------------------------------------------------------------------------
*/

Route::post('/upload', [UploadFilesController::class, 'uploadFile'])->name('designer.upload');
Route::post('/remove', [UploadFilesController::class, 'removeFile'])->name('designer.remove');
Route::post('/feedback', [UploadFilesController::class, 'getFeedback'])->name('designer.feedback');
Route::get('/submission', [SubmissionController::class, 'show'])->name('new.submission');
Route::post('/submission', [SubmissionController::class, 'store'])->name('store.submission');
Route::get('/submisssions', [SubmissionController::class, 'index'])->name('submissions.index');
Route::get('/submission/{id}', [SubmissionController::class, 'view'])->name('submissions.view');
Route::get('/download/file/{id}', [FileController::class, 'download'])->name('file.download');
Route::get('/download/zip/{id}', [FileController::class, 'downloadZip'])->name('zip.download');
