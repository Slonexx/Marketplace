<?php

use App\Http\Controllers\WebController;
use App\Http\Controllers\WebController\SupportController;
use Illuminate\Support\Facades\Route;


/*
Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [WebController::class, 'index']);
Route::get('/SupportHelp', [SupportController::class, 'support'])->name("support");
Route::post('/PostSupport', [SupportController::class, 'supportSend'])->name("Send");
