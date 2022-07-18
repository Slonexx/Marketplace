<?php


use App\Http\Controllers\Config\Vendor\VendorEndpointController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Config\user_context_loader_inc_Controller;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPropertyController;

//Vendor activate install app MoySklad
Route::get('/', [VendorEndpointController::class, 'Activate'])->name('Vendor');

Route::post('orders', [OrderController::class,'insertOrders']);
Route::post('orderStatus',[OrderController::class,'changeOrderStatus']);
Route::post('products', [ProductController::class,'insertProducts']);

Route::get('property',[ProductPropertyController::class,'getPropertiesByCategory']);
Route::get('categories',[ProductPropertyController::class,'getAllCategories']);
Route::get('values', [ProductPropertyController::class,'getValuesByPropertyCategory']);
Route::get('excelProducts', [ExcelController::class,'getProductsExcel']);


Route::get('getTest', [TestController::class,'init']);
//Route::get('getTest2', [TestController2::class,'getTest'])->name('Test');








