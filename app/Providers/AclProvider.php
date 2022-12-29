<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use App\Services\Permission\hasPermission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AclProvider extends ServiceProvider
{
    use hasPermission;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('permissions')){
            $permissions = Permission::all();
            foreach ($permissions as $permission) {
                Gate::define($permission->key, function ($user) use ($permission){
                    return $this->hasAccess($permission,$user);
                });
            }
        }
    }
}
