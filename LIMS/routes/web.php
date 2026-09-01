<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RequestFormController;


Route::get('/', [LoginController::class, 'welcome'])->name('welcome');
Route::post('/ffast', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/requestform', [RequestFormController::class, 'index'])->name('dashboard');
Route::get('/request-form', [RequestFormController::class, 'index'])
    ->name('requestform.index');

Route::post('/request-form', [RequestFormController::class, 'storeRequestForm'])
    ->name('requestform.storeRequestForm');
    

Route::middleware('admin')->group(function () {
    
});

Route::middleware('user')->group(function () {


});

