<?php

namespace App\Http\Controllers;

use App\Http\Resources\consumptionCategoryCollection;
use App\Http\Resources\consumptionCategoryResource;
use App\Models\consumptionCategory;
use App\Http\Requests\StoreconsumptionCategoryRequest;
use App\Http\Requests\UpdateconsumptionCategoryRequest;

class ConsumptionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consumptionCategory = consumptionCategory::paginate(10);
        return new consumptionCategoryCollection($consumptionCategory);
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
    public function store(StoreconsumptionCategoryRequest $request)
    {
        if (ConsumptionCategory::find($request->id)) {
            return response('Error, la categoría de consumo ya existe.', 400);
        }

        $category = ConsumptionCategory::create($request->all());
        return new consumptionCategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = ConsumptionCategory::find($id);

        if (!$category) {
            return response('Categoría de consumo no encontrada.', 404);
        }

        return new ConsumptionCategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(consumptionCategory $consumptionCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateconsumptionCategoryRequest $request, int $id)
    {
        $category = ConsumptionCategory::find($id);

        if (!$category) {
            return response('Categoría de consumo no encontrada.', 404);
        }

        $updated = $category->update($request->all());
        return response()->json(['success' => $updated]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $category = ConsumptionCategory::find($id);

        if (!$category) {
            return response('Categoría de consumo no encontrada.', 404);
        }

        $category->delete();
        return response("Eliminación completada.");
    }
}
