<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;

Route::group(['middleware' => 'JwtAuth', 'throttle:10,1'], function () {

    // Check Is Admin
    Route::group(['middleware' => 'is_admin'], function () {

        // Year Controller
        Route::group(['prefix' => 'year'], function () {
            Route::post('/create', [YearController::class, 'store']);
            Route::put('/update/{id}', [YearController::class, 'update']);
            Route::delete('/delete/{id}', [YearController::class, 'delete']);
        });

        // Division Controller
        Route::group(['prefix' => 'division'], function () {
            Route::post('/create', [DivisionController::class, 'store']);
            Route::put('/update/{id}', [DivisionController::class, 'update']);
            Route::delete('/delete/{id}', [DivisionController::class, 'delete']);
        });

        // User Controller
        Route::group(['prefix' => 'student'], function () {
            Route::get('/studentsInactive', [JWTAuthController::class, 'getStudentsInactive']);
            Route::put('/update/{id}', [JWTAuthController::class, 'update']);
        });

        // Course Controller
        Route::group(['prefix' => 'course'], function () {
            Route::post('/create', [CourseController::class, 'store']);
            Route::post('/update/{id}', [CourseController::class, 'update']);
            Route::delete('/delete/{id}', [CourseController::class, 'delete']);
        });

        // Category Controller
        Route::group(['prefix' => 'category'], function () {
            Route::post('/create', [CategoryController::class, 'store']);
            Route::put('/update/{id}', [CategoryController::class, 'update']);
        });

    });

    // User Auth
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/user', [JWTAuthController::class, 'getUser']);
        Route::post('/logout', [JWTAuthController::class, 'logout']);
    });

});

// Auth 
Route::post('/register', [JWTAuthController::class, 'register']);
Route::post('/login', [JWTAuthController::class, 'login']);

// Course Controller
Route::get('course/index', [CourseController::class, 'index']);
