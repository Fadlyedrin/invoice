<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permissions', ['only' => ['index']]);
        $this->middleware('permission:create permissions', ['only' => ['create','store']]);
        $this->middleware('permission:update permissions', ['only' => ['edit','update']]);
        $this->middleware('permission:delete permissions', ['only' => ['destroy']]);
    }
    public function index(){

        $permissions = Permission::orderBy('name')->get();
        return view('role-permission.permission.index', compact('permissions'));
    }
    public function create(){
        return view('role-permission.permission.create');
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'string',
            'unique:permissions,name'
        ]);

        Permission::create([
            'name' => $request->name
        ]);

        return redirect('permissions')->with('success', 'Permission created successfully');
    }
    public function edit(Permission $permission){

        return view('role-permission.permission.edit', compact('permission'));
    }
    public function update(Request $request, Permission $permission){
        $request->validate([
            'name' => 'required',
            'string',
            'unique:permissions,name,'.$permission->id
        ]);

        $permission->update([
            'name' => $request->name
        ]);
        return redirect('permissions')->with('success', 'Permission updated successfully');
    }
    public function destroy(Permission $permission){
        $permission->delete();
        return redirect('permissions')->with('success', 'Permission deleted successfully');
    }
}
