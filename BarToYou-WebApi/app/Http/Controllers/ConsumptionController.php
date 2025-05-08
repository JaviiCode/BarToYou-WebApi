<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexconsumptionRequest;
use App\Http\Resources\consumptionCollection;
use App\Http\Resources\consumptionResource;
use App\Models\consumption;
use App\Http\Requests\StoreconsumptionRequest;
use App\Http\Requests\UpdateconsumptionRequest;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;


class ConsumptionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bartoyou/consumptions",
     *     summary="Listar todos los consumos",
     *     tags={"Consumptions"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de consumos"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/bartoyou/consumptions",
     *     summary="Crear un nuevo consumo",
     *     tags={"Consumptions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "category_id", "image"},
     *                 @OA\Property(property="name", type="string", example="Coca-Cola"),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *                 @OA\Property(property="image", type="file", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Consumo creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/bartoyou/consumptions/{id}",
     *     summary="Obtener un consumo por ID",
     *     tags={"Consumptions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del consumo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Consumo encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Consumo no encontrado"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/bartoyou/consumptions/{id}",
     *     summary="Actualizar un consumo",
     *     tags={"Consumptions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del consumo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Pepsi"),
     *             @OA\Property(property="category_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Consumo actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Consumo no encontrado"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/bartoyou/consumptions/{id}",
     *     summary="Eliminar un consumo",
     *     tags={"Consumptions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del consumo a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Consumo eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Consumo no encontrado"
     *     )
     * )
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
