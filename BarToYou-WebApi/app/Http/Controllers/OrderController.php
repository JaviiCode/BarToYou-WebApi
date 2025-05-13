<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexorderRequest;
use App\Http\Resources\orderCollection;
use App\Http\Resources\orderResource;
use App\Models\consumptionRecipe;
use App\Models\Order;
use App\Http\Requests\StoreorderRequest;
use App\Http\Requests\UpdateorderRequest;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;



class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bartoyou/orders",
     *     summary="Listar pedidos",
     *     tags={"Orders"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de pedidos"
     *     )
     * )
     */
    public function index(IndexorderRequest $request)
    {
        $order = Order::paginate(10);
        return new orderCollection($order);
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
     *     path="/api/bartoyou/orders",
     *     summary="Crear un nuevo pedido",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="member_id", type="integer", example=1),
     *             @OA\Property(property="consumption_id", type="integer", example=10),
     *             @OA\Property(property="status_id", type="integer", example=2),
     *             @OA\Property(property="date_time", type="string", example="2025-05-08T12:30:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pedido creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="El pedido ya existe"
     *     )
     * )
     */
    public function store(StoreorderRequest $request)
    {
        if (Order::find($request->id)) {
            return response('Error, el pedido ya existe.', 400);
        }

        $customDrinkId = "#" . rand(1000, 9999);

        // Combina los datos del request con el nuevo campo
        $data = $request->all();
        $data['custom_drink_id'] = $customDrinkId;

        $order = Order::create($data);

        return new orderResource($order);
    }

    /**
     * @OA\Get(
     *     path="/api/bartoyou/orders/{id}",
     *     summary="Obtener pedido por ID",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del pedido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pedido encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pedido no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $order = Order::with(['members', 'recipes', 'status'])->find($id);

        if (!$order) {
            return response('Pedido no encontrado.', 404);
        }

        return new OrderResource($order);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/bartoyou/orders/{id}",
     *     summary="Actualizar un pedido",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del pedido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status_id", type="integer", example=2),
     *             @OA\Property(property="date_time", type="string", example="2025-05-09T13:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pedido actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pedido no encontrado"
     *     )
     * )
     */
    public function update(UpdateorderRequest $request, int $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response('Pedido no encontrado.', 404);
        }

        $updated = $order->update($request->all());
        return response()->json(['success' => $updated]);
    }

    /**
     * @OA\Delete(
     *     path="/api/bartoyou/orders/{id}",
     *     summary="Eliminar un pedido",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del pedido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pedido eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pedido no encontrado"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response('Pedido no encontrado.', 404);
        }

        $order->delete();
        return response("Eliminación completada.");
    }

    /**
     * @OA\Get(
     *     path="/api/bartoyou/orders/user/{userId}",
     *     summary="Obtener pedidos por usuario",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pedidos del usuario",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="orderid", type="integer", example=1),
     *                 @OA\Property(property="custom_drink_id", type="string", example="#1234"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="date_time", type="string", example="2025-05-08T12:30:00"),
     *                 @OA\Property(property="status", type="string", example="En preparación"),
     *                 @OA\Property(property="items", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="name", type="string", example="Bebida Personalizada"),
     *                         @OA\Property(property="ingredients", type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 @OA\Property(property="ingredient", type="string", example="Azúcar"),
     *                                 @OA\Property(property="amount", type="string", example="1 cucharadita")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron pedidos para el usuario"
     *     )
     * )
     */
    public function getOrdersByUser($userId)
    {
        $orders = Order::where('member_id', $userId)
            ->with(['status', 'Consumption'])
            ->get();

        return response()->json($orders->map(function ($order) {
            $item = [
                'orderid' => $order->id,
                'custom_drink_id' => $order->custom_drink_id,
                'user_id' => $order->member_id,
                'date_time' => $order->date_time,
                'status' => $order->status->name,
                'items' => []
            ];

            // Si es bebida normal (basado en consumo)
            if ($order->consumption_id && $order->consumption) {
                $imageUrl = $order->consumption->image_url;

                if ($imageUrl && strpos($imageUrl, '/storage/') === 0) {
                    $imageUrl = substr($imageUrl, 9);
                }

                $item['items'][] = [
                    'name' => $order->consumption->name,
                    'description' => $order->consumption->description,
                    'image_url' => $imageUrl,
                ];
            } else {
                // Bebida personalizada
                $recipes = consumptionRecipe::where('custom_drink_id', $order->custom_drink_id)
                    ->with('ingredient')
                    ->get();

                $item['items'][] = [
                    'name' => 'Bebida Personalizada',
                    'ingredients' => $recipes->map(function ($recipe) {
                        return [
                            'ingredient' => $recipe->ingredient->name ?? 'Desconocido',
                            'amount' => $recipe->ingredient_amount,
                        ];
                    })->values()
                ];
            }

            return $item;
        }));
    }
}
