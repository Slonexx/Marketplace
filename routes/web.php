<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\Config\CheckC;
use App\Http\Controllers\Data\deleteController;
use App\Http\Controllers\Web\getSetting\addController;
use App\Http\Controllers\Web\getSetting\DeviceController;
use App\Http\Controllers\Web\getSetting\DocumentController;
use App\Http\Controllers\Web\getSetting\mainController;
use App\Http\Controllers\Web\getSetting\orderController;
use App\Http\Controllers\Web\indexController;
use App\Http\Controllers\Web\postSetting\postAddController;
use App\Http\Controllers\Web\postSetting\postDeviceController;
use App\Http\Controllers\Web\postSetting\postDocumentController;
use App\Http\Controllers\Web\postSetting\postMainController;
use App\Http\Controllers\Web\postSetting\postOrderController;
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


Route::get('/Setting/main/{accountId}', [mainController::class, 'index'])->name('mainSetting');
Route::post('/Setting/main/{accountId}', [postMainController::class, 'postMainSetting']);

Route::get('/Setting/order/{accountId}', [orderController::class, 'index'])->name('orderSetting');
Route::post('/Setting/order/{accountId}', [postOrderController::class, 'postOrderSetting']);

Route::get('/Setting/add/{accountId}', [addController::class, 'index'])->name('addSetting');
Route::post('/Setting/add/{accountId}', [postAddController::class, 'postAddSetting']);


Route::get('/Setting/info/{accountId}', [indexController::class, 'reKassaInfo']);
Route::get('/Setting/Device/{accountId}', [DeviceController::class, 'getDevice']);
Route::post('/Setting/Device/{accountId}', [postDeviceController::class, 'postDevice']);

Route::get('/Setting/Document/{accountId}', [DocumentController::class, 'getDocument'])->name('getDocument');
Route::post('/Setting/Document/{accountId}', [postDocumentController::class, 'postDocument']);



Route::get('/ExportProduct/{accountId}', [ExportProductController::class, 'index'])->name('ExportProduct');

//Установка и удаление приложения
Route::get('/setAttributes/{accountId}/{tokenMs}', [AttributeController::class, 'setAttributes']);
Route::get('/delete/{accountId}', [deleteController::class, 'delete']);

/*
 * Старые роуты
Route::get('/infoLog/{accountId}', [info_log_Controller::class, 'index'])->name('InfoLog');

Route::get('/SupportHelp/{accountId}', [SupportController::class, 'support'])->name("support");
Route::post('/PostSupport/{accountId}', [SupportController::class, 'supportSend'])->name("Send");

Route::get('/Whatsapp/{accountId}', [WhatsappController::class, 'Index'])->name("whatsapp");
Route::post('/WhatsappSend/{accountId}', [WhatsappController::class, 'WhatsappSend'])->name("whatsapp_Send");*/
