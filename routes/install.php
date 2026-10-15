<?php
/*
|--------------------------------------------------------------------------
| Install Routes
|--------------------------------------------------------------------------
| Single-page browser installer. Everything required to run the application
| is collected and applied from one form, so no extra CLI step is needed.
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

Route::controller(InstallController::class)->group(function () {
    Route::get('install', 'wizard')->name('install.wizard');
    Route::post('install', 'install')->name('install.run');
});
