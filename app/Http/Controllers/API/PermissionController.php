<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
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
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'nullable|string'
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? 'web'
        ]);

        return response()->json($permission, 201);
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json($permission);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|unique:permissions,name,' . $id,
            'guard_name' => 'nullable|string'
        ]);

        $permission->update($validated);

        return response()->json($permission);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        // Check if permission is assigned to any roles
        if ($permission->roles()->exists()) {
            return response()->json(['message' => 'Cannot delete permission that is assigned to roles'], 400);
        }

        $permission->delete();
        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
