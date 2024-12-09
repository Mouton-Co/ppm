<?php

use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Settings routes
|--------------------------------------------------------------------------
*/

Route::post('/settings/update-ajax', [SettingsController::class, 'updateAjax'])->name('settings.update.ajax');
