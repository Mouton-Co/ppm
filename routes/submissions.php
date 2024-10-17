<?php

use App\Http\Controllers\Design\SubmissionController;
use App\Http\Controllers\Design\UploadFilesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| File submission routes
|--------------------------------------------------------------------------
*/

Route::post('/upload', [UploadFilesController::class, 'uploadFile'])->name('designer.upload');
Route::post('/remove', [UploadFilesController::class, 'removeFile'])->name('designer.remove');
Route::post('/feedback', [UploadFilesController::class, 'getFeedback'])->name('designer.feedback');
Route::get('/submission', [SubmissionController::class, 'show'])->name('submissions.create');
Route::post('/submission', [SubmissionController::class, 'store'])->name('store.submission');
Route::get('/submisssions', [SubmissionController::class, 'index'])->name('submissions.index');
Route::get('/submission/{id}', [SubmissionController::class, 'view'])->name('submissions.show');
Route::delete('/submission/{id}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');
Route::post('/submissions/restore/{id}', [SubmissionController::class, 'restore'])->name('submissions.restore');
Route::post('/submissions/trash/{id}', [SubmissionController::class, 'trash'])->name('submissions.trash');
Route::post('/submissions/replace', [SubmissionController::class, 'replace'])->name('submissions.replace');
