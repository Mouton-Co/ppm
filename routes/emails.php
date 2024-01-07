<?php

use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Email routes
|--------------------------------------------------------------------------
*/

Route::prefix('email')->group(function () {
    Route::get('/purchase-order/{id}', [EmailController::class, 'renderOrder'])->name('email.purchase-order.render');
    Route::post('/purchase-order/{id}', [EmailController::class, 'sendOrder'])->name('email.purchase-order.send');
});
