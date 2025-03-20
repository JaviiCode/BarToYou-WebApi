<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexingredientCategoryRequest;
use App\Http\Resources\ingredientCategoryCollection;
use App\Http\Resources\ingredientCategoryResource;
use App\Models\ingredientCategory;
use App\Http\Requests\StoreingredientCategoryRequest;
use App\Http\Requests\UpdateingredientCategoryRequest;

class IngredientCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexingredientCategoryRequest $request)
    {
        $ingredientCategory = ingredientCategory::paginate(10);
        return new ingredientCategoryCollection($ingredientCategory);
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
    public function store(StoreingredientCategoryRequest $request)
    {
        if (IngredientCategory::find($request->id)) {
            return response('Error, la categoría de ingrediente ya existe.', 400);
        }

        $category = IngredientCategory::create($request->all());
        return new ingredientCategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = IngredientCategory::find($id);

        if (!$category) {
            return response('Categoría de ingrediente no encontrada.', 404);
        }

        return new IngredientCategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ingredientCategory $ingredientCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateingredientCategoryRequest $request, int $id)
    {
        $category = IngredientCategory::find($id);

        if (!$category) {
            return response('Categoría de ingrediente no encontrada.', 404);
        }

        $updated = $category->update($request->all());
        return response()->json(['success' => $updated]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $category = IngredientCategory::find($id);

        if (!$category) {
            return response('Categoría de ingrediente no encontrada.', 404);
        }

        $category->delete();
        return response("Eliminación completada.");
    }
}
