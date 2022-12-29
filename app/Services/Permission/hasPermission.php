<?php

namespace App\Services\Permission;

trait hasPermission
{
    public function hasAccess($permission,$user)
    {
        if ($this->hasPermission($permission,$user) || $this->hasRole($permission,$user)) {
            return true;
        }
        return false;
    }

    public function hasRole($permission,$user)
    {
        $permissionRoles = $permission->roles();

        if ($user->roles()->where('system_role', 'FULL_ADMIN')->exists()) {
            return true;
        }

        $permissionRoles = $user->roles()->whereHas('permissions', function ($q1) use ($permission) {
            $q1->where('permissions.id', $permission->id);
        })->get();

        if ($permissionRoles->count() < 0) {
            return true;
        }
        return false;
    }

    public function hasPermission($permission,$user)
    {
        if ($user->perms->contains($permission)) {
            return true;
        }
        return false;
    }
}
