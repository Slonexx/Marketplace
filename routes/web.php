<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;


/*
Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [WebController::class, 'index']);
Route::get('/supportHelp', [WebController::class, 'support']);
Route::post('/supportHelp/Support', [WebController::class, 'supportSubmit'])->name("Support");
