<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexingredientRequest;
use App\Http\Resources\ingredientCollection;
use App\Http\Resources\ingredientResource;
use App\Models\ingredient;
use App\Http\Requests\StoreingredientRequest;
use App\Http\Requests\UpdateingredientRequest;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexingredientRequest $request)
    {
        $ingredient = ingredient::paginate(10);
        return new ingredientCollection($ingredient);
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
    public function store(StoreingredientRequest $request)
    {
        if (Ingredient::find($request->id)) {
            return response('Error, el ingrediente ya existe.', 400);
        }

        $ingredient = Ingredient::create($request->all());
        return new ingredientResource($ingredient);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ingredient = Ingredient::with('category')->find($id);

        if (!$ingredient) {
            return response('Ingrediente no encontrado.', 404);
        }

        return new IngredientResource($ingredient);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ingredient $ingredient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateingredientRequest $request, int $id)
    {
        $ingredient = Ingredient::find($id);

        if (!$ingredient) {
            return response('Ingrediente no encontrado.', 404);
        }

        $updated = $ingredient->update($request->all());
        return response()->json(['success' => $updated]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $ingredient = Ingredient::find($id);

        if (!$ingredient) {
            return response('Ingrediente no encontrado.', 404);
        }

        $ingredient->delete();
        return response("Eliminación completada.");
    }
}
