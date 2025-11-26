<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
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

    public function index(Request $request)
    {
        $query = User::with('roles', 'tenant');

        if ($request->has('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->has('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->paginate(15);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'nullable|string',
            'tenant_id' => 'nullable|exists:tenants,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'tenant',
            'tenant_id' => $validated['tenant_id'] ?? null,
            'is_verified' => true // Admin created users are auto-verified
        ]);

        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return response()->json($user->load('roles'), 201);
    }

    public function show($id)
    {
        $user = User::with('roles', 'tenant')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|unique:users,phone,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'nullable|string',
            'tenant_id' => 'nullable|exists:tenants,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'is_verified' => 'sometimes|boolean'
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return response()->json($user->load('roles'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting self
        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'Cannot delete yourself'], 400);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function assignRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::findOrFail($id);
        $user->assignRole($validated['role']);

        return response()->json(['message' => 'Role assigned successfully']);
    }

    public function removeRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::findOrFail($id);
        $user->removeRole($validated['role']);

        return response()->json(['message' => 'Role removed successfully']);
    }
}
