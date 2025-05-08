<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexconsumptionCategoryRequest;
use App\Http\Resources\consumptionCategoryCollection;
use App\Http\Resources\consumptionCategoryResource;
use App\Models\consumptionCategory;
use App\Http\Requests\StoreconsumptionCategoryRequest;
use App\Http\Requests\UpdateconsumptionCategoryRequest;
use OpenApi\Annotations as OA;


class ConsumptionCategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bartoyou/consumption-categories",
     *     summary="Listar categorías de consumo",
     *     tags={"Consumption Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado paginado de categorías"
     *     )
     * )
     */
    public function index(IndexconsumptionCategoryRequest $request)
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
     * @OA\Post(
     *     path="/api/bartoyou/consumption-categories",
     *     summary="Crear una nueva categoría de consumo",
     *     tags={"Consumption Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "nombre"},
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Bebidas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoría creada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="La categoría ya existe"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/bartoyou/consumption-categories/{id}",
     *     summary="Obtener una categoría de consumo por ID",
     *     tags={"Consumption Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/bartoyou/consumption-categories/{id}",
     *     summary="Actualizar una categoría de consumo",
     *     tags={"Consumption Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Comida")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/bartoyou/consumption-categories/{id}",
     *     summary="Eliminar una categoría de consumo",
     *     tags={"Consumption Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Eliminación completada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     )
     * )
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
