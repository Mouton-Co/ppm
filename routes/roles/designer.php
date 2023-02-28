<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Designer routes
|--------------------------------------------------------------------------
*/

Route::middleware('designer')->group(function () {
    Route::get('/designer-dashboard', [DashboardController::class, 'designer'])->name('dashboard.designer');
});
