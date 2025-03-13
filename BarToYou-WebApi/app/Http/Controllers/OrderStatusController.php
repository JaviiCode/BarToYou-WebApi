<?php

namespace App\Http\Controllers;

use App\Http\Resources\orderStatusCollection;
use App\Http\Resources\orderStatusResource;
use App\Models\orderStatus;
use App\Http\Requests\StoreorderStatusRequest;
use App\Http\Requests\UpdateorderStatusRequest;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderStatus = orderStatus::paginate(10);
        return new orderStatusCollection($orderStatus);
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
    public function store(StoreorderStatusRequest $request)
    {
        if (OrderStatus::find($request->id)) {
            return response('Error, el estado de pedido ya existe.', 400);
        }

        $status = OrderStatus::create($request->all());
        return new orderStatusResource($status);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $status = OrderStatus::find($id);

        if (!$status) {
            return response('Estado de pedido no encontrado.', 404);
        }

        return new OrderStatusResource($status);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(orderStatus $orderStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateorderStatusRequest $request, int $id)
    {
        $status = OrderStatus::find($id);

        if (!$status) {
            return response('Estado de pedido no encontrado.', 404);
        }

        $updated = $status->update($request->all());
        return response()->json(['success' => $updated]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $status = OrderStatus::find($id);

        if (!$status) {
            return response('Estado de pedido no encontrado.', 404);
        }

        $status->delete();
        return response("Eliminación completada.");
    }
}
