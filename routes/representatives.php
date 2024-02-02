<?php

use App\Http\Controllers\RepresentativeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Representative routes
|--------------------------------------------------------------------------
*/

Route::resource('representatives', RepresentativeController::class)->except(['show']);
