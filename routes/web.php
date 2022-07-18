<?php

use App\Http\Controllers\Config\Vendor\VendorEndpointController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\WebController\SupportController;
use App\Http\Controllers\WebController\Setting_mainController;
use App\Http\Controllers\WebController\WhatsappController;
use Illuminate\Support\Facades\Route;


//Setting Vendor



Route::get('/{id}/', [WebController::class, 'index']);

Route::get('/Setting', [Setting_mainController::class, 'index'])->name("Setting_Main");
Route::post('/SettingSend', [Setting_mainController::class, 'saveApiKey'])->name("Setting_Send");

Route::get('/SupportHelp', [SupportController::class, 'support'])->name("support");
Route::post('/PostSupport', [SupportController::class, 'supportSend'])->name("Send");

Route::get('/Whatsapp', [WhatsappController::class, 'Index'])->name("whatsapp");
Route::post('/WhatsappSend', [WhatsappController::class, 'WhatsappSend'])->name("whatsapp_Send");

