<?php

namespace App\Http\Controllers;

use App\Http\Resources\comsumptionRecipeCollection;
use App\Http\Resources\consumptionCategoryCollection;
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(consumptionRecipe $consumptionRecipe)
    {
        //
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
    public function update(UpdateconsumptionRecipeRequest $request, consumptionRecipe $consumptionRecipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(consumptionRecipe $consumptionRecipe)
    {
        //
    }
}
