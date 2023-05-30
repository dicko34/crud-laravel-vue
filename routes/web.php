<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::controller('LogController')->group(function () {
    Route::get('crud','index');
    Route::post('create' ,'store');
    Route::put('crud/{user}/edit' , 'update');
    Route::delete('crud/{user}/delete' , 'destroy');
});


