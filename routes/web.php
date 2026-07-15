<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('simple.auth')->group(function () {
    Route::get('/', [DataController::class, 'index'])->name('data.index');
    Route::get('/import', [DataController::class, 'importForm'])->name('data.import.form');
    Route::post('/import', [DataController::class, 'importStore'])->name('data.import.store');
    Route::get('/data', [DataController::class, 'dataTable'])->name('data.table');
    Route::post('/cell-color-update', [DataController::class, 'updateCellColor'])->name('data.cell.color');
    Route::post('/cell-value-update', [DataController::class, 'updateCellValue'])->name('data.cell.value');
    Route::post('/batch-update-color', [DataController::class, 'batchUpdateColor'])->name('data.batch.color');
    Route::post('/delete/{id}', [DataController::class, 'destroy'])->name('data.destroy');
    Route::post('/batch-delete', [DataController::class, 'batchDestroy'])->name('data.batch.destroy');
    Route::get('/colors/{column}', [DataController::class, 'getColors'])->name('data.colors');

    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('users.index');
        Route::get('/create', 'create')->name('users.create');
        Route::post('/', 'store')->name('users.store');
        Route::get('/{id}/edit', 'edit')->name('users.edit');
        Route::put('/{id}', 'update')->name('users.update');
        Route::delete('/{id}', 'destroy')->name('users.destroy');
    });
});
