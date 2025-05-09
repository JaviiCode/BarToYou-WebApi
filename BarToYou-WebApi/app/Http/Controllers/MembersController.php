<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexmembersRequest;
use App\Http\Resources\membersCollection;
use App\Http\Resources\membersResource;
use App\Models\members;
use App\Http\Requests\StoremembersRequest;
use App\Http\Requests\UpdatemembersRequest;
use App\Models\role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class MembersController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/bartoyou/members",
     *     summary="Listar miembros",
     *     tags={"Members"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de miembros"
     *     )
     * )
     */
    public function index(IndexmembersRequest $request)
    {
        $members = members::paginate(10);
        return new membersCollection($members);
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
     *     path="/api/bartoyou/members",
     *     summary="Crear un nuevo miembro",
     *     tags={"Members"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="usuario123"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="role_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Miembro creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="El miembro ya existe"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="El nombre ya está registrado"
     *     )
     * )
     */
    public function store(StoremembersRequest $request)
    {
        if (members::find($request->id)) {
            return response()->json(['message' => 'Error, usuario ya existe.'], 400);
        }
        if (members::where('name', $request->name)->exists()) {
            return response()->json(['message' => 'El nombre ya está registrado.'], 409);
        }
        $hashedPassword = Hash::make($request->password);

        $member = members::create([
            'name' => $request->name,
            'password' => $hashedPassword,
            'role_id' => $request->role_id,
            'token' => null,
            'expiration_date_token' => null,
        ]);

        return new membersResource($member);
    }

    /**
     * @OA\Get(
     *     path="/api/bartoyou/members/{id}",
     *     summary="Obtener miembro por ID",
     *     tags={"Members"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del miembro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Miembro encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Miembro no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $member = members::with('role')->find($id);

        if (!$member) {
            return response('Miembro no encontrado.', 404);
        }

        return new membersResource($member);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(members $members)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/bartoyou/members/{id}",
     *     summary="Actualizar miembro",
     *     tags={"Members"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del miembro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="usuario123_editado"),
     *             @OA\Property(property="role_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Miembro actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Miembro no encontrado"
     *     )
     * )
     */
    public function update(UpdatemembersRequest $request, int $id)
    {
        $member = members::find($id);

        if (!$member) {
            return response('Miembro no encontrado.', 404);
        }

        $updated = $member->update($request->all());
        return response()->json(['success' => $updated]);
    }

    /**
     * @OA\Delete(
     *     path="/api/bartoyou/members/{id}",
     *     summary="Eliminar miembro",
     *     tags={"Members"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del miembro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Miembro eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Miembro no encontrado"
     *     )
     * )
     */
    public function destroy(int $id)
    {
        $member = members::find($id);

        if (!$member) {
            return response('Miembro no encontrado.', 404);
        }

        //Eliminar relaciones con las ordenes
        $member->orders()->delete();

        $member->delete();
        return response("Eliminación completada.");
    }

    /**
     * @OA\Post(
     *     path="/api/check-token",
     *     summary="Verificar validez de un token",
     *     tags={"Members"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="abc123xyz")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token válido, miembro retornado"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido o expirado"
     *     )
     * )
     */
    public function checkToken(Request $request)
    {
        // Validar que el token esté presente en la solicitud
        $request->validate([
            'token' => 'required|string',
        ]);

        // Obtener el token de la solicitud
        $token = $request->input('token');

        // Buscar al miembro por el token en la tabla members
        $member = DB::table('members')
            ->where('token', $token)
            ->first();

        // Si no se encuentra el miembro, devolver false
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido.',
            ], 401);
        }

        // Verificar si el token ha expirado
        $now = Carbon::now();
        if ($member->expiration_date_token && $now->greaterThan($member->expiration_date_token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token expirado.',
            ], 401);
        }

        // Devolver los datos del miembro
        return response()->json([
            'success' => true,
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'role_id' => $member->role_id,
                'token' => $member->token,
                'expiration_date_token' => $member->expiration_date_token,
            ],
        ]);
    }
}
