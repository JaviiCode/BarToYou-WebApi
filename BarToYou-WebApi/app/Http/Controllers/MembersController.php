<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexmembersRequest;
use App\Http\Resources\membersCollection;
use App\Http\Resources\membersResource;
use App\Models\members;
use App\Http\Requests\StoremembersRequest;
use App\Http\Requests\UpdatemembersRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $member = members::find($id);

        if (!$member) {
            return response('Miembro no encontrado.', 404);
        }

        $member->delete();
        return response("Eliminación completada.");
    }

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
