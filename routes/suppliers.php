<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Supplier routes
|--------------------------------------------------------------------------
*/

Route::resource('suppliers', SupplierController::class)->except(['show']);
