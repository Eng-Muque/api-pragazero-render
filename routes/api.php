<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Product
|--------------------------------------------------------------------------
*/

use App\Modules\Product\Controllers\ProductController;
use App\Modules\Product\Controllers\AdminProductController;

/*
|--------------------------------------------------------------------------
| Service
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Admin\AdminServiceController;

/*
|--------------------------------------------------------------------------
| Orders
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AdminOrderManagementController;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Quotations
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\QuotationController;

/*
|--------------------------------------------------------------------------
| Hero
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\HeroController;


/*
|--------------------------------------------------------------------------
| Públicas
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

Route::get('/hero', [HeroController::class, 'index']);


/*
|--------------------------------------------------------------------------
| Autenticadas
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    /*
    |--------------------------------------------------------------------------
    | Cliente
    |--------------------------------------------------------------------------
    */

    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/my-orders', [OrderController::class, 'myOrders']);

    Route::post('/quotations', [QuotationController::class, 'store']);
    Route::get('/my-quotations', [QuotationController::class, 'myQuotations']);



    /*
    |--------------------------------------------------------------------------
    | Administração
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin')
        ->prefix('admin')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Produtos
            |--------------------------------------------------------------------------
            */

            Route::apiResource('products', AdminProductController::class);
            Route::patch('/products/{id}/status', [AdminProductController::class, 'toggleStatus']);

            /*
            |--------------------------------------------------------------------------
            | Serviços
            |--------------------------------------------------------------------------
            */

            Route::apiResource('services', AdminServiceController::class);

            /*
            |--------------------------------------------------------------------------
            | Utilizadores
            |--------------------------------------------------------------------------
            */

            Route::get('/users-list', [AuthController::class, 'listAllUsers']);
            Route::put('/users-list/{id}/role', [AuthController::class, 'updateRole']);

            /*
            |--------------------------------------------------------------------------
            | Pedidos
            |--------------------------------------------------------------------------
            */

            Route::get('/orders', [AdminOrderManagementController::class, 'listOrders']);
            Route::put('/orders/{id}/confirm', [AdminOrderManagementController::class, 'updateStatus']);
            Route::delete('/orders/{id}', [AdminOrderManagementController::class, 'destroy']);

            /*
            |--------------------------------------------------------------------------
            | Orçamentos
            |--------------------------------------------------------------------------
            */

            Route::get('/quotations', [AdminOrderManagementController::class, 'listQuotations']);
            Route::put('/quotations/{id}/price', [AdminOrderManagementController::class, 'updateQuotationPrice']);
            Route::delete('/quotations/{id}', [AdminOrderManagementController::class, 'destroyQuotation']);

            /*
            |--------------------------------------------------------------------------
            | Hero
            |--------------------------------------------------------------------------
            */

            Route::get('/hero', [HeroController::class, 'adminIndex']);
            Route::post('/hero', [HeroController::class, 'store']);
            Route::put('/hero/{id}', [HeroController::class, 'update']);
            Route::patch('/hero/{id}/status', [HeroController::class, 'toggleStatus']);
            Route::delete('/hero/{id}', [HeroController::class, 'destroy']);
        });
});