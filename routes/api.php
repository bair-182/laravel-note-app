<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
});

Route::middleware('auth:api')->group(function (){
    Route::post('notes', [\App\Http\Controllers\NoteController::class, 'store']);
    Route::get('notes', [\App\Http\Controllers\NoteController::class, 'index']);
    Route::put('notes/{id}', [\App\Http\Controllers\NoteController::class, 'update']);
    Route::delete('notes/{id}', [\App\Http\Controllers\NoteController::class, 'delete']);
});

Route::get('/send-email', [\App\Http\Controllers\MailController::class, 'sendEmail']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


