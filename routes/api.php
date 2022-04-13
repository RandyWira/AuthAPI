<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\API\AuthController;
use App\Http\API\TodoController;

Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
//API route for login user
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('todo', App\Http\Controllers\API\TodoController::class);

    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });


    // API route for logout user
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
});
