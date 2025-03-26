<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexconsumptionRecipeRequest;
use App\Http\Resources\comsumptionRecipeCollection;
use App\Http\Resources\consumptionCategoryCollection;
use App\Http\Resources\consumptionRecipeResource;
use App\Models\consumptionRecipe;
use App\Models\consumption;
use App\Models\order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StoreconsumptionRecipeRequest;
use App\Http\Requests\UpdateconsumptionRecipeRequest;

class ConsumptionRecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexconsumptionRecipeRequest $request)
    {
        $consumptionRecipe = consumptionRecipe::paginate(10);
        return new comsumptionRecipeCollection($consumptionRecipe);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreconsumptionRecipeRequest $request)
    {
        if (ConsumptionRecipe::find($request->id)) {
            return response('Error, la receta de consumo ya existe.', 400);
        }

        $recipe = ConsumptionRecipe::create($request->all());
        return new consumptionRecipeResource($recipe);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $recipe = ConsumptionRecipe::with(['consumption', 'ingredient'])->find($id);

        if (!$recipe) {
            return response('Receta de consumo no encontrada.', 404);
        }

        return new ConsumptionRecipeResource($recipe);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(consumptionRecipe $consumptionRecipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateconsumptionRecipeRequest $request, int $id)
    {
        $recipe = ConsumptionRecipe::find($id);

        if (!$recipe) {
            return response('Receta de consumo no encontrada.', 404);
        }

        $updated = $recipe->update($request->all());
        return response()->json(['success' => $updated]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $recipe = ConsumptionRecipe::find($id);

        if (!$recipe) {
            return response('Receta de consumo no encontrada.', 404);
        }

        $recipe->delete();
        return response("Eliminación completada.");
    }

    public function createCustomDrink(Request $request)
    {
        // Validación de la entrada
        $request->validate([
            'user_id' => 'required|integer',
            'base_drink' => 'required|string',
            'base_drink_id' => 'required|integer',
            'ingredients' => 'required|array',
            'ingredients.*.ingredient_id' => 'required|integer',
            'ingredients.*.amount' => 'required|numeric',
            'ice' => 'nullable|boolean',
            'ice_type' => 'nullable|string|in:normal,picado',
        ]);

        // Generar un custom_drink_id único
        $customDrinkId = '#'.rand(1000, 9999);

        // Crear la nueva orden para la bebida personalizada
        $order = Order::create([
            'member_id' => $request->user_id,
            'date_time' => Carbon::now(),
            'quantity' => 1,
            'status_id' => 1,
            'custom_drink_id' => $customDrinkId,
        ]);

        // Agregar la bebida base seleccionada por el usuario a la tabla ConsumptionRecipe
        ConsumptionRecipe::create([
            'consumption_id' => $request->base_drink_id,
            'ingredient_id' => $request->base_drink_id,
            'ingredient_amount' => 50.00,
            'ingredient_unit' => 'ml',
            'custom_drink_id' => $customDrinkId,
        ]);

        foreach ($request->ingredients as $ingredient) {
            ConsumptionRecipe::create([
                'consumption_id' => $ingredient['consumption_id'],
                'ingredient_id' => $ingredient['ingredient_id'],
                'ingredient_amount' => $ingredient['amount'],
                'ingredient_unit' => $ingredient['unit'],
                'custom_drink_id' => $customDrinkId,
            ]);
        }

        if ($request->has('ice') && $request->ice) {
            $iceId = Consumption::where('name', 'Hielo')->first()->id ?? null;

            if ($iceId) {
                $iceType = $request->ice_type ?? 'normal';
                ConsumptionRecipe::create([
                    'consumption_id' => $iceId,
                    'ingredient_id' => 1,
                    'ingredient_amount' => 1,
                    'ingredient_unit' => $iceType,
                    'custom_drink_id' => $customDrinkId,
                ]);
            }
        }
        return response()->json([
            'custom_drink_id' => $customDrinkId,
            'order_id' => $order->id,
            'status' => 'success',
        ]);
    }
}
