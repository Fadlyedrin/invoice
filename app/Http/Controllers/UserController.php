<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:index users', ['only' => ['index']]);
        $this->middleware('permission:create users', ['only' => ['create','store']]);
        $this->middleware('permission:update users', ['only' => ['edit','update']]);
        $this->middleware('permission:delete users', ['only' => ['destroy']]);
    }
    public function index()
    {
        $users = User::all();
        return view('role-permission.user.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('role-permission.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'max:255', 'min:2'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:5', 'max:255'],
            'roles' => ['required'],
            'address' => ['nullable', 'max:100'],
            'city' => ['nullable', 'max:100'],
            'phone' => ['nullable', 'regex:/^(\\+62|62|08)[1-9][0-9]{7,11}$/']
        ],[
            'roles.required' => 'Role cannot be empty.',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'city' => $request->city,
            'phone' => $request->phone,
        ]);

        $user->syncRoles(is_array($request->roles) ? $request->roles : [$request->roles]);

        return redirect('users')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name', 'name')->all();
        return view('role-permission.user.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => ['required', 'max:255', 'min:2'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:5', 'max:255'],
            'roles' => ['required', 'array'],
            'address' => ['nullable', 'max:100'],
            'city' => ['nullable', 'max:100'],
            'phone' => ['nullable', 'regex:/^(\\+62|62|08)[1-9][0-9]{7,11}$/']
        ],[
            'roles.required' => 'Role cannot be empty.',
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if (!empty($request->roles)) {
            $user->syncRoles(is_array($request->roles) ? $request->roles : [$request->roles]);
        }

        return redirect('users')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->roles()->detach();
        $user->delete();
        return redirect('users')->with('success', 'User deleted successfully');
    }
}
