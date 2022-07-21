<?php

use App\Http\Controllers\WebController\Setting_mainController;
use App\Http\Controllers\WebController\SupportController;
use App\Http\Controllers\WebController\WebController;
use App\Http\Controllers\WebController\WhatsappController;
use Illuminate\Support\Facades\Route;


Route::get('/', [WebController::class, 'index']);

Route::get('/Check/{id}', [\App\Http\Controllers\Config\CheckC::class, 'index'])->name('Check');

Route::get('/{id}', [WebController::class, 'show'])->name("Index");

Route::get('/Setting/{id}', [Setting_mainController::class, 'index'])->name("Setting_Main");
Route::post('/SettingSend/{id}', [Setting_mainController::class, 'postFormSetting'])->name("Setting_Send");

Route::get('/SupportHelp/{id}', [SupportController::class, 'support'])->name("support");
Route::post('/PostSupport/{id}', [SupportController::class, 'supportSend'])->name("Send");

Route::get('/Whatsapp/{id}', [WhatsappController::class, 'Index'])->name("whatsapp");
Route::post('/WhatsappSend/{id}', [WhatsappController::class, 'WhatsappSend'])->name("whatsapp_Send");

