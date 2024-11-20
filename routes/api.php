<?php

use Illuminate\Support\Facades\Route;
use App\Models\Governorate;
use App\Http\Controllers\ {
    YearController,
    CourseController,
    SectionController,
    JWTAuthController,
    CategoryController,
    DivisionController,
    QuestionController,
    AnswerController,
    CodeController,
    InvoiceController,
    RechargeController,
    AdminController
};

// Route::group(['middleware' => 'throttle:10,1'], function () {

    Route::group(['middleware' => 'JwtAuth'], function () {

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
            
            // Section Controller
            Route::group(['prefix' => 'section'], function () {
                Route::post('/create', [SectionController::class, 'store']);
                Route::put('/update/{id}', [SectionController::class, 'update']);
            });

            // Question Controller
            Route::group(['prefix' => 'question'], function () {
                Route::post('/create', [QuestionController::class, 'store']);
            });

            // Codes Controller
            Route::group(['prefix' => 'code'], function () {
                Route::post('/store', [CodeController::class, 'store']);
                Route::get('/index', [CodeController::class, 'index']);
            });

        });

        // Show Section Controller
        Route::group(['prefix' => 'section'], function () {
            Route::get('/show/{id}', [SectionController::class, 'show']);
        });

        // Recharge Controller
        Route::group(['prefix' => 'recharge'], function () {
            Route::get('/index', [RechargeController::class, 'index']);
            Route::post('/store', [RechargeController::class, 'store']);
        });
        
        // Admin Controller
        Route::group(['prefix' => 'admin'], function () {
            Route::get('/index', [AdminController::class, 'index']);
            Route::post('/store', [AdminController::class, 'store']);
        });

        // Invoice Controller
        Route::group(['prefix' => 'invoice'], function () {
            Route::get('/get-all-invoices', [InvoiceController::class, 'get_all_invoices']);
            Route::get('/my-courses', [InvoiceController::class, 'my_courses']);
            Route::get('/show/{id}', [InvoiceController::class, 'show']);
            Route::post('/store', [InvoiceController::class, 'store']);
            Route::put('/pay/{id}', [InvoiceController::class, 'pay']);
        });

        // Answer Controller
        Route::group(['prefix' => 'answer'], function () {
            Route::post('/store', [AnswerController::class, 'store']);
            Route::get('/get-all-degrees', [AnswerController::class, 'get_all_degrees']);
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
    Route::group(['prefix' => 'course'], function () {
        Route::get('/index', [CourseController::class, 'index']);
        Route::get('/show/{id}', [CourseController::class, 'show']);
    });

    // Division Controller
    Route::get('division/index', [DivisionController::class, 'index']);

    // Year Controller
    Route::get('year/index', [YearController::class, 'index']);

    // Governorates Controller
    Route::get('governorate/index', function () {
        return Governorate::all();
    });

// });
