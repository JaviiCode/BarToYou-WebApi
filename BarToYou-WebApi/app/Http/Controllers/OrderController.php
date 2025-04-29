<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexorderRequest;
use App\Http\Resources\orderCollection;
use App\Http\Resources\orderResource;
use App\Models\consumptionRecipe;
use App\Models\order;
use App\Http\Requests\StoreorderRequest;
use App\Http\Requests\UpdateorderRequest;
use Illuminate\Support\Facades\Storage;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexorderRequest $request)
    {
        $order = order::paginate(10);
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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

    public function getOrdersByUser($userId)
    {
        $orders = Order::where('member_id', $userId)
            ->with(['status', 'consumption'])
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
