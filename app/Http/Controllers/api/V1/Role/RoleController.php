<?php

namespace App\Http\Controllers\api\V1\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Role\RoleRequest;
use App\Http\Requests\Api\V1\Role\storeRoleRequest;
use App\Http\Requests\Api\V1\Role\updateRoleRequest;
use App\Http\Resources\Role\roleCollection;
use App\Http\Resources\Role\roleResource;
use App\Models\Role;

class RoleController extends Controller
{
    public function store(storeRoleRequest $request)
    {
        Role::create([
            'key' => $request->input('key'),
            'name' => $request->input('name'),
            'system_role' => $request->input('system_role')
        ]);

        return $this->sendSuccess('','Role created Successfully');
    }

    public function destroy(RoleRequest $request)
    {
        Role::find($request->input('id'))->delete();
        return $this->sendSuccess('','Role Deleted Successfully');
    }

    public function update(updateRoleRequest $request){
        $role = Role::find($request->input('role_id'));
        $role->update([
            "system_role" => $request->input('system_role'),
            "key" => $request->input('key'),
            "name" => $request->input('name')
        ]);

        $resourceRole = new roleResource($role);
        return $this->sendSuccess($resourceRole,'Role updated successfully');
    }

    public function showAll()
    {
        $roles = Role::all();
        $roleCollection = new roleCollection($roles);
        return $this->sendSuccess($roleCollection,'Roles list:');
    }

    public function show(RoleRequest $request)
    {
        $user = Role::find($request->input('id'));
        $userResourse = new roleResource($user);
        return $this->sendSuccess($userResourse,'Role information:');
    }
}
