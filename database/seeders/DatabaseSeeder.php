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

        DB::table('categories')->insert([
            'name' => 'category 1',
            'slug' => 'first-category',
            'description' => 'this is first category',
            'status' => 1,
            'pic' => 'img.png'
        ]);

        DB::table('brands')->insert([
            'name' => 'brand 1',
            'slug' => 'first-brand',
            'logo' => 'logo.png',
            'category_id' => 1,
            'status' => 1
        ]);

        DB::table('products')->insert([
            "picture" => 'test.png',
            "name" => "product 1",
            "slug" => "product-1-slug",
            "description" => "this is description for products",
            "status" => 1,
            "key_words" => "nike,tshirt",
            "price" => 500,
            "status_store" => 1,
            "view_count" => 0,
            "code" => 1,
            "sell_count" => 4,
            "category_id" => 1,
            "brand_id" => 1,
        ]);
    }
}
