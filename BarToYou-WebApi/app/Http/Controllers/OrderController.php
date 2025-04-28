<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexorderRequest;
use App\Http\Resources\orderCollection;
use App\Http\Resources\orderResource;
use App\Models\consumptionRecipe;
use App\Models\order;
use App\Http\Requests\StoreorderRequest;
use App\Http\Requests\UpdateorderRequest;

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

        $order = Order::create($request->all());
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
            ->with(['status'])
            ->get();

        return response()->json($orders->groupBy('custom_drink_id')->map(function ($group) {
            // Obtener el custom_drink_id
            $customDrinkId = $group->first()->custom_drink_id;

            // Obtener todas las recetas de este custom_drink_id
            $recipes = consumptionRecipe::where('custom_drink_id', $customDrinkId)
                ->with('ingredient')
                ->get();

            return [
                'orderid' => $group->first()->id,
                'custom_drink_id' => $customDrinkId,
                'user_id' => $group->first()->member_id,
                'date_time' => $group->first()->date_time,
                'status' => $group->first()->status->name,
                'items' => [
                    [
                        'name' => 'Bebida Personalizada',
                        'ingredients' => $recipes->map(function ($recipe) {
                            return [
                                'ingredient' => $recipe->ingredient->name ?? 'Desconocido',
                                'amount' => $recipe->ingredient_amount,
                            ];
                        })->values(),
                    ]
                ]
            ];
        })->values());
    }

}
