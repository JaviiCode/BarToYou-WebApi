<?php

namespace App\Http\Controllers;

use App\Http\Resources\roleCollection;
use App\Http\Resources\roleResource;
use App\Models\role;
use App\Http\Requests\StoreroleRequest;
use App\Http\Requests\UpdateroleRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show($id)
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
