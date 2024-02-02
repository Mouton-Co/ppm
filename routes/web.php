<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

    include 'submissions.php';
    include 'parts.php';
    include 'users.php';
    include 'files.php';
    include 'orders.php';
    include 'emails.php';
    include 'suppliers.php';
});

include __DIR__ . '/auth.php';
