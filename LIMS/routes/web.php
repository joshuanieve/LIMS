<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\LoginController;
Route::get('/', [LoginController::class, 'welcome'])->name('welcome');
Route::post('/ffast', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


use App\Http\Controllers\AnalysisController;
Route::get('/analysislist/data', [AnalysisController::class, 'data']);


use App\Http\Controllers\DashboardController;
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');           // route page


use App\Http\Controllers\RequestFormController;
Route::get('/requestform', [RequestFormController::class, 'index'])->name('requestform.index');                             // route page
Route::post('/requestform', [RequestFormController::class, 'storeRequestForm'])->name('requestform.storeRequestForm');      // insert form
    

use App\Http\Controllers\RequestSampleController;
Route::get('/requestsample', [RequestSampleController::class, 'index'])->name('requestsample.index');           // route page
Route::get('/requestsample/{id}', [RequestSampleController::class, 'data'])->name('requestsample.data');        // load table
Route::get('/requestsample/view/{id}', [RequestSampleController::class, 'view'])->name('requestsample.view');   // view


use App\Http\Controllers\JobRoutingController;
Route::get('/jobrouting', [JobRoutingController::class, 'index'])->name('jobrouting.index');           // route page
Route::get('/jobrouting/data', [JobRoutingController::class, 'data'])->name('jobrouting.data');        // load table


Route::middleware('admin')->group(function () {
    
});

Route::middleware('user')->group(function () {


});

