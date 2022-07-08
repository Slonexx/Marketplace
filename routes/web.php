<?php

use App\Http\Controllers\WebController;
use App\Http\Controllers\WebController\SupportController;
use App\Http\Controllers\WebController\Setting_mainController;
use Illuminate\Support\Facades\Route;


/*
Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [WebController::class, 'index']);
Route::get('/Setting', [Setting_mainController::class, 'index'])->name("Setting_Main");
Route::post('/SettingSend', [Setting_mainController::class, 'saveApiKey'])->name("Setting_Send");
Route::get('/SupportHelp', [SupportController::class, 'support'])->name("support");
Route::post('/PostSupport', [SupportController::class, 'supportSend'])->name("Send");
