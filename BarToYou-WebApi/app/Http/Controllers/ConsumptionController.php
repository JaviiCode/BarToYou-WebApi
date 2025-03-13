<?php

namespace App\Http\Controllers;

use App\Http\Resources\consumptionCollection;
use App\Http\Resources\consumptionResource;
use App\Models\consumption;
use App\Http\Requests\StoreconsumptionRequest;
use App\Http\Requests\UpdateconsumptionRequest;

class ConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consumption = consumption::paginate(10);
        return new consumptionCollection($consumption);
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
    public function store(StoreconsumptionRequest $request)
    {
        if (Consumption::find($request->id)) {
            return response('Error, el consumo ya existe.', 400);
        }

        $consumption = Consumption::create($request->all());
        return new consumptionResource($consumption);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $consumption = Consumption::with('category')->find($id);

        if (!$consumption) {
            return response('Consumo no encontrado.', 404);
        }

        return new ConsumptionResource($consumption);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(consumption $consumption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateconsumptionRequest $request, int $id)
    {
        $consumption = Consumption::find($id);

        if (!$consumption) {
            return response('Consumo no encontrado.', 404);
        }

        $updated = $consumption->update($request->all());
        return response()->json(['success' => $updated]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $consumption = Consumption::find($id);

        if (!$consumption) {
            return response('Consumo no encontrado.', 404);
        }

        $consumption->delete();
        return response("Eliminación completada.");
    }
}
