<?php

use Illuminate\Support\Facades\Route;

Route::resource('autofill-suppliers', \App\Http\Controllers\AutofillSupplierController::class)
    ->except(['show']);
