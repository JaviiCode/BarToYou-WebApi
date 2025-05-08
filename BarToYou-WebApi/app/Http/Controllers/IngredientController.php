<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexingredientRequest;
use App\Http\Resources\ingredientCollection;
use App\Http\Resources\ingredientResource;
use App\Models\ingredient;
use App\Http\Requests\StoreingredientRequest;
use App\Http\Requests\UpdateingredientRequest;
use OpenApi\Annotations as OA;


class IngredientController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bartoyou/ingredients",
     *     summary="Listar ingredientes",
     *     tags={"Ingredients"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de ingredientes"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/bartoyou/ingredients",
     *     summary="Crear un nuevo ingrediente",
     *     tags={"Ingredients"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Vodka"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="unit", type="string", example="ml")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ingrediente creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="El ingrediente ya existe"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/bartoyou/ingredients/{id}",
     *     summary="Obtener un ingrediente por ID",
     *     tags={"Ingredients"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del ingrediente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ingrediente encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ingrediente no encontrado"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/bartoyou/ingredients/{id}",
     *     summary="Actualizar un ingrediente",
     *     tags={"Ingredients"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del ingrediente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Zumo de piña"),
     *             @OA\Property(property="category_id", type="integer", example=2),
     *             @OA\Property(property="unit", type="string", example="ml")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ingrediente actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ingrediente no encontrado"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/bartoyou/ingredients/{id}",
     *     summary="Eliminar un ingrediente",
     *     tags={"Ingredients"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del ingrediente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ingrediente eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ingrediente no encontrado"
     *     )
     * )
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
