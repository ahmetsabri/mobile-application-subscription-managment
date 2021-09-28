<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\InAppPruchaseController;
use App\Http\Controllers\ReciptController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('register', [DeviceController::class, 'register']);
Route::get('pruchase', [InAppPruchaseController::class, 'pruchase']);
Route::get('check-subscription', SubscriptionController::class);
