<?php

namespace App\Http\Controllers;

use App\Http\Resources\membersCollection;
use App\Http\Resources\membersResource;
use App\Models\members;
use App\Http\Requests\StoremembersRequest;
use App\Http\Requests\UpdatemembersRequest;
use Illuminate\Support\Facades\Hash;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
}
