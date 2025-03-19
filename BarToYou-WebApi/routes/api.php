<?php

use App\Http\Controllers\ConsumptionCategoryController;
use App\Http\Controllers\ConsumptionController;
use App\Http\Controllers\ConsumptionRecipeController;
use App\Http\Controllers\IngredientCategoryController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//, 'middleware' => 'auth:sanctum' (Poner justo aqui abajo)
Route::group(['prefix' => 'bartoyou'], function () {

    Route::group(['middleware' => 'authMiddleware:Administrador'], function () {
        Route::apiResource('members', MembersController::class);
        Route::apiResource('roles', RoleController::class);
    });

    Route::group(['middleware' => 'authMiddleware:Camarero'], function () {
        Route::apiResource('orders', OrderController::class);
    });

    //Route::apiResource('members', MembersController::class);
    //Route::apiResource('roles', RoleController::class);
    Route::apiResource('consumptions', ConsumptionController::class);
    Route::apiResource('consumption-categories', ConsumptionCategoryController::class);
    Route::apiResource('ingredients', IngredientController::class);
    Route::apiResource('ingredient-categories', IngredientCategoryController::class);
    Route::apiResource('consumption-recipes', ConsumptionRecipeController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('order-statuses', OrderStatusController::class);
});
