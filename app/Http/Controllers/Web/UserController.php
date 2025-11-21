<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'nullable|email|unique:users,email',
            'phone'=>'nullable|string|unique:users,phone',
            'password'=>'required|string|min:6|confirmed',
            'role'=>'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'] ?? null,
            'phone'=>$data['phone'] ?? null,
            'password'=>Hash::make($data['password'])
        ]);

        $user->assignRole($data['role']);

        return redirect()->route('users.index')->with('success','User created.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>"nullable|email|unique:users,email,{$user->id}",
            'phone'=>"nullable|string|unique:users,phone,{$user->id}",
            'password'=>'nullable|string|min:6|confirmed',
            'role'=>'required|exists:roles,name',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'] ?? $user->email;
        $user->phone = $data['phone'] ?? $user->phone;
        if (!empty($data['password'])) $user->password = Hash::make($data['password']);
        $user->save();

        $user->syncRoles([$data['role']]);

        return redirect()->route('users.index')->with('success','User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success','User deleted.');
    }
}
