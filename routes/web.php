<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\Config\CheckC;
use App\Http\Controllers\Data\deleteController;
use App\Http\Controllers\Web\indexController;
use App\Http\Controllers\WebController\info_log_Controller;
use App\Http\Controllers\WebController\Setting_mainController;
use App\Http\Controllers\WebController\SupportController;
use App\Http\Controllers\WebController\WebController;
use App\Http\Controllers\WebController\WhatsappController;
use App\Http\Controllers\WebController\ExportProductController;
use Illuminate\Support\Facades\Route;

Route::get('/Check/{accountId}', [CheckC::class, 'index'])->name('Check');


Route::get('/', [indexController::class, 'index']);
Route::get('/{accountId}/', [indexController::class, 'indexShow'])->name("Index");

Route::get('/Setting/{accountId}', [Setting_mainController::class, 'index'])->name("Setting_Main");
Route::post('/SettingSend/{accountId}', [Setting_mainController::class, 'postFormSetting'])->name("Setting_Send");

Route::get('/SupportHelp/{accountId}', [SupportController::class, 'support'])->name("support");
Route::post('/PostSupport/{accountId}', [SupportController::class, 'supportSend'])->name("Send");

Route::get('/Whatsapp/{accountId}', [WhatsappController::class, 'Index'])->name("whatsapp");
Route::post('/WhatsappSend/{accountId}', [WhatsappController::class, 'WhatsappSend'])->name("whatsapp_Send");

Route::get('/ExportProduct/{accountId}', [ExportProductController::class, 'index'])->name('ExportProduct');


Route::get('/infoLog/{accountId}', [info_log_Controller::class, 'index'])->name('InfoLog');


Route::get('/setAttributes/{accountId}/{tokenMs}', [AttributeController::class, 'setAttributes']);
Route::get('/delete/{accountId}', [deleteController::class, 'delete']);
