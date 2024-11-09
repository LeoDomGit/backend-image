<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Http\Requests\RoleRequest;
use Inertia\Inertia;

class RolesController extends Controller
{
    /**
     * Display a listing of roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $roles = Roles::all();
       return Inertia::render('Roles/Index', ['roles' => $roles]);
    }

    /**
     * Store a new role.
     *
     * @param  RoleRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RoleRequest $request)
    {
        return parent::store_ft($request, Roles::class);
    }

    /**
     * Update an existing role.
     *
     * @param  RoleRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RoleRequest $request, $id)
    {
        return parent::update_ft($request, Roles::class, $id);
    }

    /**
     * Delete a role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $role = Roles::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully'], 200);
    }
}
