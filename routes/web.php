<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
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
    Route::get('/pill-html', [Controller::class, 'getPillHtml'])->name('pill-html');
    Route::post('/update-configs', [Controller::class, 'updateConfigs'])->name('update-configs');

    include 'submissions.php';
    include 'parts.php';
    include 'users.php';
    include 'files.php';
    include 'orders.php';
    include 'emails.php';
    include 'suppliers.php';
    include 'representatives.php';
    include 'autofill-supplier.php';
    include 'process-types.php';
    include 'projects.php';
    include 'project-statuses.php';
    include 'project-responsibles.php';
    include 'recipient-groups.php';
    include 'roles.php';
});

include __DIR__ . '/auth.php';

Route::get('/orders/complete/{id}', [OrderController::class, 'markOrdered'])->name('orders.complete');
Route::get('/confirmation', function () {
    return view('order.confirmation');
});
