<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'guard_name' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? 'web'
        ]);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return response()->json($role->load('permissions'), 201);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role->update($validated);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return response()->json($role->load('permissions'));
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Prevent deleting admin role
        if ($role->name === 'admin') {
            return response()->json(['message' => 'Cannot delete admin role'], 400);
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function assignPermission(Request $request, $id)
    {
        $validated = $request->validate([
            'permission' => 'required|exists:permissions,name'
        ]);

        $role = Role::findOrFail($id);
        $role->givePermissionTo($validated['permission']);

        return response()->json(['message' => 'Permission assigned successfully']);
    }

    public function removePermission(Request $request, $id)
    {
        $validated = $request->validate([
            'permission' => 'required|exists:permissions,name'
        ]);

        $role = Role::findOrFail($id);
        $role->revokePermissionTo($validated['permission']);

        return response()->json(['message' => 'Permission removed successfully']);
    }
}
