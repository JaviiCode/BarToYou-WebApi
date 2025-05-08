<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexingredientCategoryRequest;
use App\Http\Resources\ingredientCategoryCollection;
use App\Http\Resources\ingredientCategoryResource;
use App\Models\ingredientCategory;
use App\Http\Requests\StoreingredientCategoryRequest;
use App\Http\Requests\UpdateingredientCategoryRequest;
use OpenApi\Annotations as OA;


class IngredientCategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bartoyou/ingredient-categories",
     *     summary="Listar categorías de ingredientes",
     *     tags={"Ingredient Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de categorías"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/bartoyou/ingredient-categories",
     *     summary="Crear una nueva categoría de ingrediente",
     *     tags={"Ingredient Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Lácteos"),
     *             @OA\Property(property="description", type="string", example="Productos derivados de la leche")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoría creada correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="La categoría ya existe"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/bartoyou/ingredient-categories/{id}",
     *     summary="Obtener una categoría de ingrediente por ID",
     *     tags={"Ingredient Categories"},
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
     * @OA\Put(
     *     path="/api/bartoyou/ingredient-categories/{id}",
     *     summary="Actualizar una categoría de ingrediente",
     *     tags={"Ingredient Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Cítricos"),
     *             @OA\Property(property="description", type="string", example="Frutas cítricas")
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
     * @OA\Delete(
     *     path="/api/bartoyou/ingredient-categories/{id}",
     *     summary="Eliminar una categoría de ingrediente",
     *     tags={"Ingredient Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     )
     * )
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
