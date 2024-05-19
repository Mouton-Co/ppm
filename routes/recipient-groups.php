<?php

use App\Http\Controllers\RecipientGroupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Recipient Group Routes
|--------------------------------------------------------------------------
*/

Route::resource('recipient-groups', RecipientGroupController::class)->except(['show']);
