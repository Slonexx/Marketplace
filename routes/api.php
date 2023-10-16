<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\CheckSettingsController;
use App\Http\Controllers\Config\Vendor\VendorEndpointController;
use \App\Http\Controllers\Config\DeleteVendorApiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Web\CheckAllTokenMS;
use App\Http\Controllers\Web\WebhookMSController;
use App\Http\Controllers\WebHookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPropertyController;
use App\Http\Controllers\SettingController;

Route::post('CheckAllTokenMS', [CheckAllTokenMS::class,'CheckAllTokenMS']);


Route::post('orders', [OrderController::class,'insertOrders']);
Route::post('orderStatus',[OrderController::class,'changeOrderStatus']);
Route::post('products', [ProductController::class,'insertProducts']);

Route::get('property',[ProductPropertyController::class,'getPropertiesByCategory']);
Route::get('categories',[ProductPropertyController::class,'getAllCategories']);
Route::get('values', [ProductPropertyController::class,'getValuesByPropertyCategory']);
Route::post('excelProducts/{TokenMoySklad}', [ExcelController::class,'getProductsExcel'])->name('ExcelProducts');

Route::post('setAttributes', [AttributeController::class, 'createAllAttributes']);

Route::get('checkSettings', [SettingController::class, 'getSettings']);
Route::get('checkSettings2', [CheckSettingsController::class, 'haveSettings']);

Route::get('DeleteVendorApi/{appId}/{accountId}', [DeleteVendorApiController::class, 'Delete'])->name('Delete');

Route::get('getTest', [TestController::class,'init']);
//Route::get('getTest2', [TestController2::class,'getTest'])->name('Test');



//ReKASSA
Route::post('attributes',[AttributeController::class,'setAllAttributesR']);

Route::post('ticket',[TicketController::class,'initTicket']);

Route::get('ticket',[TicketController::class,'getUrlTicket']);
//Route::post('cancelTicket',[TicketController::class,'cancelTicket']);

Route::post('closeShift',[ShiftController::class,'closeShift']);

Route::post('webhook/{accountId}/customerorder',[WebHookController::class,'newOrder']);
Route::post('webhook/{accountId}/demand',[WebHookController::class,'newDemand']);



Route::post('/webhook/customerorder/',[WebhookMSController::class, 'customerorder']);
Route::post('/webhook/demand/',[WebhookMSController::class, 'customerorder']);
Route::post('/webhook/salesreturn/',[WebhookMSController::class, 'customerorder']);






