<?php

use Illuminate\Support\Facades\Route;

Route::resource('process-types', \App\Http\Controllers\ProcessTypeController::class)
    ->except(['show']);
