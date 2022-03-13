<?php

use App\Http\Controllers\CalculateController;
use App\Http\Controllers\OvertimesController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

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

Route::put('/settings', [SettingController::class, 'index']);

Route::post('/employees', [EmployeesController::class, 'index']);
Route::get('/employees' , [EmployeesController::class, 'show']);

Route::post('/overtimes', [OvertimesController::class, 'createOvertimes']);
Route::get('/overtimes', [OvertimesController::class, 'showOvertimes']);

Route::get('/overtime-pays/calculate', [CalculateController::class, 'calculate']);


