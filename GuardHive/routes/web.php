<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\SettingsController;





Route::get('/', function () {
        return redirect()->route('login');
});

Route::get('/monitoring', [MonitoringController::class, 'showDetectedImages'])->name('monitoring');
Route::get('/monitoring/daily', [MonitoringController::class, 'showDetectedImages'])->name('monitoring.daily')->defaults('report', 'daily');
Route::get('/monitoring/weekly', [MonitoringController::class, 'showDetectedImages'])->name('monitoring.weekly')->defaults('report', 'weekly');
Route::get('/monitoring/monthly', [MonitoringController::class, 'showDetectedImages'])->name('monitoring.monthly')->defaults('report', 'monthly');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::get('/settings', function () {
    // return the settings view or your controller method here
    return view('settings'); // or whatever your settings page is
})->name('settings');
