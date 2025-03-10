<?php

namespace App\Http\Controllers;

use App\Models\ingredient;
use App\Http\Requests\StoreingredientRequest;
use App\Http\Requests\UpdateingredientRequest;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ingredient $ingredient)
    {
        //
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
    public function update(UpdateingredientRequest $request, ingredient $ingredient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ingredient $ingredient)
    {
        //
    }
}
