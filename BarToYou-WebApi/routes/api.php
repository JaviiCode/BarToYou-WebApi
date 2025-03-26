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

Route::post('/check-token', [MembersController::class, 'checkToken']);
//Rutas de pedidos personalizados
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/user/{userId}', [OrderController::class, 'getOrdersByUser']);
//Ruta para crear bebidas custom
Route::post('/custom-drink', [ConsumptionRecipeController::class, 'createCustomDrink']);


//, 'middleware' => 'authMiddleware' (Poner justo aqui abajo)
Route::group(['prefix' => 'bartoyou', 'middleware' => 'authMiddleware'], function () {

    Route::apiResource('members', MembersController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('consumptions', ConsumptionController::class);
    Route::apiResource('consumption-categories', ConsumptionCategoryController::class);
    Route::apiResource('ingredients', IngredientController::class);
    Route::apiResource('ingredient-categories', IngredientCategoryController::class);
    Route::apiResource('consumption-recipes', ConsumptionRecipeController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('order-statuses', OrderStatusController::class);
});
