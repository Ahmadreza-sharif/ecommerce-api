<?php

namespace App\Http\Controllers\api\V1\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Permission\addPermissionRequest;
use App\Http\Requests\Api\V1\Permission\PermissionRequest;
use App\Models\Permission;
use App\Models\Role;

class PermissionController extends Controller
{
    public function showAll()
    {
        $perms = Permission::all();
        return $this->sendSuccess($perms,'List of Permissions');
    }

    public function attach(addPermissionRequest $request)
    {
        $role = Role::find($request->input('role_id'));
        $role->permissions()->sync($request->input('permissions'));
        return $this->sendSuccess('','Permissions Added Successfully');
    }

    public function detach(PermissionRequest $request)
    {
        $role = Role::find($request->input('role_id'));
        $role->permissions()->detach();
        return $this->sendSuccess('','Role Detached successfully');
    }

    public function show(PermissionRequest $request)
    {
        $role = Role::find($request->input('role_id'));
        return $this->sendSuccess($role->permissions,$role->name . " Permissions:");
    }
}
