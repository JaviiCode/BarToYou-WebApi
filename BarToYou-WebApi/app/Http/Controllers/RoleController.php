<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexroleRequest;
use App\Http\Resources\roleCollection;
use App\Http\Resources\roleResource;
use App\Models\role;
use App\Http\Requests\StoreroleRequest;
use App\Http\Requests\UpdateroleRequest;
use OpenApi\Annotations as OA;


class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bartoyou/roles",
     *     summary="Listar roles",
     *     tags={"Roles"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de roles"
     *     )
     * )
     */
    public function index(IndexroleRequest $request)
    {
        $role = role::paginate(10);
        return new roleCollection($role);
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
     *     path="/api/bartoyou/roles",
     *     summary="Crear un nuevo rol",
     *     tags={"Roles"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Administrador"),
     *             @OA\Property(property="description", type="string", example="Rol con acceso completo a la plataforma")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rol creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="El rol ya existe"
     *     )
     * )
     */
    public function store(StoreroleRequest $request)
    {
        if (Role::find($request->id)) {
            return response('Error, el rol ya existe.', 400);
        }

        $role = Role::create($request->all());
        return new roleResource($role);
    }

    /**
     * @OA\Get(
     *     path="/api/bartoyou/roles/{id}",
     *     summary="Obtener rol por ID",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */
    public function show(int $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response('Rol no encontrado.', 404);
        }

        return new roleResource($role);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(role $role)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/bartoyou/roles/{id}",
     *     summary="Actualizar un rol",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Moderador"),
     *             @OA\Property(property="description", type="string", example="Rol con permisos limitados")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */
    public function update(UpdateroleRequest $request, int $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response('Rol no encontrado.', 404);
        }

        $updated = $role->update($request->all());
        return response()->json(['success' => $updated]);
    }

    /**
     * @OA\Delete(
     *     path="/api/bartoyou/roles/{id}",
     *     summary="Eliminar un rol",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response('Rol no encontrado.', 404);
        }

        $role->delete();
        return response("Eliminación completada.");
    }
}
