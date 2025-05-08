<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexconsumptionRecipeRequest;
use App\Http\Resources\comsumptionRecipeCollection;
use App\Http\Resources\consumptionCategoryCollection;
use App\Http\Resources\consumptionRecipeResource;
use App\Models\consumptionRecipe;
use App\Models\consumption;
use App\Models\order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StoreconsumptionRecipeRequest;
use App\Http\Requests\UpdateconsumptionRecipeRequest;
use OpenApi\Annotations as OA;


class ConsumptionRecipeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bartoyou/consumption-recipes",
     *     summary="Listar todas las recetas de consumo",
     *     tags={"Consumption Recipes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de recetas paginada"
     *     )
     * )
     */
    public function index(IndexconsumptionRecipeRequest $request)
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
     * @OA\Post(
     *     path="/api/bartoyou/consumption-recipes",
     *     summary="Crear una nueva receta de consumo",
     *     tags={"Consumption Recipes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="consumption_id", type="integer", example=1),
     *             @OA\Property(property="ingredient_id", type="integer", example=2),
     *             @OA\Property(property="ingredient_amount", type="number", format="float", example=50.0),
     *             @OA\Property(property="ingredient_unit", type="string", example="ml"),
     *             @OA\Property(property="custom_drink_id", type="string", example="#1234")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Receta creada correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="La receta ya existe"
     *     )
     * )
     */
    public function store(StoreconsumptionRecipeRequest $request)
    {
        if (ConsumptionRecipe::find($request->id)) {
            return response('Error, la receta de consumo ya existe.', 400);
        }

        $recipe = ConsumptionRecipe::create($request->all());
        return new consumptionRecipeResource($recipe);
    }

    /**
     * @OA\Get(
     *     path="/api/bartoyou/consumption-recipes/{id}",
     *     summary="Obtener una receta de consumo por ID",
     *     tags={"Consumption Recipes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la receta",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Receta encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Receta no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $recipe = ConsumptionRecipe::with(['consumption', 'ingredient'])->find($id);

        if (!$recipe) {
            return response('Receta de consumo no encontrada.', 404);
        }

        return new ConsumptionRecipeResource($recipe);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(consumptionRecipe $consumptionRecipe)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/bartoyou/consumption-recipes/{id}",
     *     summary="Actualizar una receta de consumo",
     *     tags={"Consumption Recipes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la receta",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="ingredient_amount", type="number", format="float", example=60.0),
     *             @OA\Property(property="ingredient_unit", type="string", example="ml")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Receta actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Receta no encontrada"
     *     )
     * )
     */
    public function update(UpdateconsumptionRecipeRequest $request, int $id)
    {
        $recipe = ConsumptionRecipe::find($id);

        if (!$recipe) {
            return response('Receta de consumo no encontrada.', 404);
        }

        $updated = $recipe->update($request->all());
        return response()->json(['success' => $updated]);
    }

    /**
     * @OA\Delete(
     *     path="/api/bartoyou/consumption-recipes/{id}",
     *     summary="Eliminar una receta de consumo",
     *     tags={"Consumption Recipes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la receta",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Receta eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Receta no encontrada"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        $recipe = ConsumptionRecipe::find($id);

        if (!$recipe) {
            return response('Receta de consumo no encontrada.', 404);
        }

        $recipe->delete();
        return response("Eliminación completada.");
    }

    /**
     * @OA\Post(
     *     path="/api/custom-drink",
     *     summary="Crear una bebida personalizada",
     *     tags={"Custom Drink"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "base_drink", "base_drink_id", "ingredients"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="base_drink", type="string", example="Ron"),
     *             @OA\Property(property="base_drink_id", type="integer", example=5),
     *             @OA\Property(
     *                 property="ingredients",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="ingredient_id", type="integer", example=2),
     *                     @OA\Property(property="amount", type="number", format="float", example=30.5),
     *                     @OA\Property(property="unit", type="string", example="ml"),
     *                     @OA\Property(property="consumption_id", type="integer", example=5)
     *                 )
     *             ),
     *             @OA\Property(property="ice", type="boolean", example=true),
     *             @OA\Property(property="ice_type", type="string", example="picado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bebida personalizada creada correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function createCustomDrink(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'base_drink' => 'required|string',
            'base_drink_id' => 'required|integer',
            'ingredients' => 'required|array',
            'ingredients.*.ingredient_id' => 'required|integer',
            'ingredients.*.amount' => 'required|numeric',
            'ice' => 'nullable|boolean',
            'ice_type' => 'nullable|string|in:normal,picado',
        ]);

        $customDrinkId = "#" . rand(1000, 9999);

        // Crear la orden
        $order = Order::create([
            'member_id' => $request->user_id,
            'date_time' => Carbon::now(),
            'quantity' => 1,
            'status_id' => 1,
            'custom_drink_id' => $customDrinkId,
        ]);

        // Insertar la base de la bebida
        $baseRecipe = ConsumptionRecipe::create([
            'consumption_id' => $request->base_drink_id,
            'ingredient_id' => $request->base_drink_id,
            'ingredient_amount' => 50.00,
            'ingredient_unit' => 'ml',
            'custom_drink_id' => $customDrinkId,
        ]);

        foreach ($request->ingredients as $ingredient) {
            ConsumptionRecipe::create([
                'consumption_id' => $ingredient['consumption_id'],
                'ingredient_id' => $ingredient['ingredient_id'],
                'ingredient_amount' => $ingredient['amount'],
                'ingredient_unit' => $ingredient['unit'],
                'custom_drink_id' => $customDrinkId,
            ]);
        }

        if ($request->has('ice') && $request->ice) {
            $iceId = Consumption::where('name', 'Hielo')->first()->id ?? null;
            if ($iceId) {
                $iceType = $request->ice_type ?? 'normal';
                ConsumptionRecipe::create([
                    'consumption_id' => $iceId,
                    'ingredient_id' => 1,
                    'ingredient_amount' => 1,
                    'ingredient_unit' => $iceType,
                    'custom_drink_id' => $customDrinkId,
                ]);
            }
        }

        // Obtener el primer ConsumptionRecipe insertado
        $firstRecipe = ConsumptionRecipe::where('custom_drink_id', $customDrinkId)->first();


        // Asignar la receta a la orden
        $order->consumption_recipe_id = $firstRecipe->id;
        $order->save();

        return response()->json([
            'custom_drink_id' => $customDrinkId,
            'order_id' => $order->id,
            'status' => 'success',
        ]);
    }

}
