<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register-jobseeker', [AuthController::class, 'registerJobseeker']);
Route::post('/register-employee', [AuthController::class, 'registerEmployee']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/update-profile-jobseeker', [AuthController::class, 'updateProfileJobseeker']);
    Route::put('/update-profile-employer', [AuthController::class, 'updateProfileEmployer']);
    Route::put('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/submit-application', [ApplicationController::class, 'submitApplication']);

    Route::apiResource('jobs', JobController::class);
});
Route::get('/jobseekers', [AuthController::class, 'jobSeekers']);
Route::get('/metrics', [AuthController::class, 'metrics']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
