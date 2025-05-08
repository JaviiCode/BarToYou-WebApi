<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexconsumptionRequest;
use App\Http\Resources\consumptionCollection;
use App\Http\Resources\consumptionResource;
use App\Models\consumption;
use App\Http\Requests\StoreconsumptionRequest;
use App\Http\Requests\UpdateconsumptionRequest;
use Illuminate\Support\Str;

class ConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexconsumptionRequest $request)
    {
        $consumption = consumption::all();
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
        $request->validate([
            'name' => 'required|string|max:100',
            'category_id' => 'required|integer|exists:ConsumptionCategory,id',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $image = $request->file('image');

        // Limpiar y generar nombre único
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $cleanName = Str::slug($originalName);
        $extension = $image->getClientOriginalExtension();
        $uniqueFilename = $cleanName . '-' . Str::random(10) . '.' . $extension;

        // Guardar en public/Drinks
        $destinationPath = public_path('Drinks');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $image->move($destinationPath, $uniqueFilename);

        // Ruta relativa para guardar en la base de datos
        $imageUrl = "/drinks/" . $uniqueFilename;

        $drink = Consumption::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'image_url' => $imageUrl
        ]);

        return response()->json([
            'message' => 'Bebida añadida con éxito',
            'drink' => $drink
        ], 201);
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
