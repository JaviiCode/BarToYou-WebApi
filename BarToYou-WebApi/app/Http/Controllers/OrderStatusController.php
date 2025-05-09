<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexorderStatusRequest;
use App\Http\Resources\orderStatusCollection;
use App\Http\Resources\orderStatusResource;
use App\Models\orderStatus;
use App\Http\Requests\StoreorderStatusRequest;
use App\Http\Requests\UpdateorderStatusRequest;
use OpenApi\Annotations as OA;

class OrderStatusController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bartoyou/order-statuses",
     *     summary="Listar estados de pedidos",
     *     tags={"OrderStatuses"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de estados de pedidos"
     *     )
     * )
     */
    public function index(IndexorderStatusRequest $request)
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
     * @OA\Post(
     *     path="/api/bartoyou/order-statuses",
     *     summary="Crear un nuevo estado de pedido",
     *     tags={"OrderStatuses"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="En preparación"),
     *             @OA\Property(property="description", type="string", example="El pedido está siendo preparado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Estado de pedido creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="El estado de pedido ya existe"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/bartoyou/order-statuses/{id}",
     *     summary="Obtener estado de pedido por ID",
     *     tags={"OrderStatuses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del estado de pedido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado de pedido encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estado de pedido no encontrado"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/bartoyou/order-statuses/{id}",
     *     summary="Actualizar un estado de pedido",
     *     tags={"OrderStatuses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del estado de pedido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Completado"),
     *             @OA\Property(property="description", type="string", example="El pedido ha sido completado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado de pedido actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estado de pedido no encontrado"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/bartoyou/order-statuses/{id}",
     *     summary="Eliminar un estado de pedido",
     *     tags={"OrderStatuses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del estado de pedido",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado de pedido eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Estado de pedido no encontrado"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        $status = OrderStatus::find($id);

        if (!$status) {
            return response('Estado de pedido no encontrado.', 404);
        }

        $status->deleteRelations();

        $status->delete();
        return response("Eliminación completada.");
    }
}
