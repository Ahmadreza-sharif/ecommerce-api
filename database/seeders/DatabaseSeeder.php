<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create();
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class
        ]);
        DB::table('role_user')->insert([
            'user_id' => 1,
            "role_id" => 1
        ]);

    }
}
