<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Services\Permission\hasPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perms = new Permission();
        foreach ($perms->getPermissions() as $group) {
            foreach ($group as $permission) {
                Permission::create($permission);
            }
        }
    }
}
