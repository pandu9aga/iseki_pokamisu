<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DataController::class, 'index'])->name('data.index');
Route::get('/import', [DataController::class, 'importForm'])->name('data.import.form');
Route::post('/import', [DataController::class, 'importStore'])->name('data.import.store');
Route::get('/data', [DataController::class, 'dataTable'])->name('data.table');
Route::post('/cell-color-update', [DataController::class, 'updateCellColor'])->name('data.cell.color');
Route::post('/cell-value-update', [DataController::class, 'updateCellValue'])->name('data.cell.value');
Route::post('/batch-update-color', [DataController::class, 'batchUpdateColor'])->name('data.batch.color');
Route::post('/delete/{id}', [DataController::class, 'destroy'])->name('data.destroy');
Route::get('/colors/{column}', [DataController::class, 'getColors'])->name('data.colors');
