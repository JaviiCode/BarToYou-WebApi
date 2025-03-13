<?php

namespace App\Http\Controllers;

use App\Http\Resources\comsumptionRecipeCollection;
use App\Http\Resources\consumptionCategoryCollection;
use App\Http\Resources\consumptionRecipeResource;
use App\Models\consumptionRecipe;
use App\Http\Requests\StoreconsumptionRecipeRequest;
use App\Http\Requests\UpdateconsumptionRecipeRequest;

class ConsumptionRecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
}
