<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\QueueController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('users', UserController::class);
Route::post('users/import', [UserController::class, 'import']);
Route::delete('users/company/{company_id}', [UserController::class, 'deleteUserByCompanyId']);

Route::prefix('storages')->group(function () {
    Route::get('/', [StorageController::class, 'list']);
    Route::post('/', [StorageController::class, 'create']);
    Route::post('/{storage}', [StorageController::class, 'store']);
});

Route::prefix('queues')->group(function () {
    Route::post('/', [QueueController::class, 'create']);
});