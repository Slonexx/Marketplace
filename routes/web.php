<?php

use App\Http\Controllers\WebController\Setting_mainController;
use App\Http\Controllers\WebController\SupportController;
use App\Http\Controllers\WebController\WebController;
use App\Http\Controllers\WebController\WhatsappController;
use App\Http\Controllers\WebController\ExportProductController;
use App\Http\Controllers\webController\infoLogController;
use Illuminate\Support\Facades\Route;



Route::get('/', [WebController::class, 'index']);

Route::get('/Check/{accountId}', [\App\Http\Controllers\Config\CheckC::class, 'index'])->name('Check');

Route::get('/{accountId}', [WebController::class, 'show'])->name("Index");

Route::get('/Setting/{accountId}', [Setting_mainController::class, 'index'])->name("Setting_Main");
Route::post('/SettingSend/{accountId}', [Setting_mainController::class, 'postFormSetting'])->name("Setting_Send");

Route::get('/SupportHelp/{accountId}', [SupportController::class, 'support'])->name("support");
Route::post('/PostSupport/{accountId}', [SupportController::class, 'supportSend'])->name("Send");

Route::get('/Whatsapp/{accountId}', [WhatsappController::class, 'Index'])->name("whatsapp");
Route::post('/WhatsappSend/{accountId}', [WhatsappController::class, 'WhatsappSend'])->name("whatsapp_Send");

Route::get('/ExportProduct/{accountId}', [ExportProductController::class, 'index'])->name('ExportProduct');


Route::get('/InfoLog/{accountId}', [infoLogController::class, 'index'])->name('InfoLog');

