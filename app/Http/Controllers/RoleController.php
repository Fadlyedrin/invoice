<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:roles', ['only' => ['index']]);
        $this->middleware('permission:create roles', ['only' => ['create','store', 'addPermissionToRole','givePermissionToRole']]);
        $this->middleware('permission:update roles', ['only' => ['edit','update']]);
        $this->middleware('permission:delete roles', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = Role::get();
        return view('role-permission.role.index', compact('roles'));
    }

    public function create()
    {
        return view('role-permission.role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name']
        ]);

        Role::create([
            'name' => $request->name
        ]);

        return redirect('roles')->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        return view('role-permission.role.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name,'.$role->id]
        ]);

        $role->update([
            'name' => $request->name
        ]);
        
        return redirect('roles')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect('roles')->with('success', 'Role deleted successfully');
    }

    public function addPermissionToRole($roleID){
        $permissions = Permission::get();
        $role = Role::findOrFail($roleID);
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all();
        
        return view('role-permission.role.add-permission', compact('role', 'permissions', 'rolePermissions'));
    }

    public function givePermissionToRole(Request $request, $roleId){
        $request->validate([
            'permission' => 'required'
        ]);

        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permission);
        return redirect('roles')->with('success', 'Permision added to role successfully');
    }
}