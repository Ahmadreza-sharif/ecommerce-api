<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    const CATEGORY = 'category';
    const PRODUCT = 'product';
    const BRAND = 'brand';
    const ORDER = 'order';
    const CART = 'cart';
    const VOUCHER = 'voucher';
    const PERMISSION = 'permission';
    const ROLE = 'role';

    use HasFactory;

    protected $fillable = ['key', 'name', 'group'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }

    public function getPermissions()
    {
        return [
            self::CATEGORY => [
                [
                    'key' => "store-" . self::CATEGORY,
                    "name" => "store " . self::CATEGORY,
                    "group" => self::CATEGORY
                ],
                [
                    'key' => "show-" . self::CATEGORY,
                    "name" => "show " . self::CATEGORY,
                    "group" => self::CATEGORY
                ],
                [
                    'key' => "update-" . self::CATEGORY,
                    "name" => "update " . self::CATEGORY,
                    "group" => self::CATEGORY
                ],
                [
                    'key' => "destroy-" . self::CATEGORY,
                    "name" => "destroy " . self::CATEGORY,
                    "group" => self::CATEGORY
                ],
                [
                    'key' => "showAll-" . self::CATEGORY,
                    "name" => "showAll " . self::CATEGORY,
                    "group" => self::CATEGORY
                ],
                [
                    'key' => "status-" . self::CATEGORY,
                    "name" => "status " . self::CATEGORY,
                    "group" => self::CATEGORY
                ],
            ],

            self::BRAND => [
                [
                    'key' => "store-" . self::BRAND,
                    "name" => "store " . self::BRAND,
                    "group" => self::BRAND
                ],
                [
                    'key' => "update-" . self::BRAND,
                    "name" => "update " . self::BRAND,
                    "group" => self::BRAND
                ],
                [
                    'key' => "status-" . self::BRAND,
                    "name" => "status " . self::BRAND,
                    "group" => self::BRAND
                ],
                [
                    'key' => "destroy-" . self::BRAND,
                    "name" => "destroy " . self::BRAND,
                    "group" => self::BRAND
                ],
                [
                    'key' => "show-" . self::BRAND,
                    "name" => "show " . self::BRAND,
                    "group" => self::BRAND
                ],
                [
                    'key' => "showAll-" . self::BRAND,
                    "name" => "show " . self::BRAND,
                    "group" => self::BRAND
                ],
            ],

            self::PRODUCT => [
                [
                    'key' => "store-" . self::PRODUCT,
                    "name" => "store " . self::PRODUCT,
                    "group" => self::PRODUCT
                ],
                [
                    'key' => "update-" . self::PRODUCT,
                    "name" => "update " . self::PRODUCT,
                    "group" => self::PRODUCT
                ],
                [
                    'key' => "status-" . self::PRODUCT,
                    "name" => "status " . self::PRODUCT,
                    "group" => self::PRODUCT
                ],
                [
                    'key' => "destroy-" . self::PRODUCT,
                    "name" => "destroy " . self::PRODUCT,
                    "group" => self::PRODUCT
                ],
                [
                    'key' => "show-" . self::PRODUCT,
                    "name" => "show " . self::PRODUCT,
                    "group" => self::PRODUCT
                ],
                [
                    'key' => "showAll-" . self::PRODUCT,
                    "name" => "showAll " . self::PRODUCT,
                    "group" => self::PRODUCT
                ],
            ],

            self::ORDER => [
                [
                    'key' => "store-" . self::ORDER,
                    "name" => "store " . self::ORDER,
                    "group" => self::ORDER
                ],
                [
                    'key' => "show-" . self::ORDER,
                    "name" => "show " . self::ORDER,
                    "group" => self::ORDER
                ],
                [
                    'key' => "destroy-" . self::ORDER,
                    "name" => "destroy " . self::ORDER,
                    "group" => self::ORDER
                ],
                [
                    'key' => "update-" . self::ORDER,
                    "name" => "update " . self::ORDER,
                    "group" => self::ORDER
                ],
            ],
            self::VOUCHER => [
                [
                    'key' => "store-" . self::VOUCHER,
                    "name" => "store " . self::VOUCHER,
                    "group" => self::VOUCHER
                ],
                [
                    'key' => "show-" . self::VOUCHER,
                    "name" => "show " . self::VOUCHER,
                    "group" => self::VOUCHER
                ],
                [
                    'key' => "destroy-" . self::VOUCHER,
                    "name" => "destroy " . self::VOUCHER,
                    "group" => self::VOUCHER
                ],
                [
                    'key' => "update-" . self::VOUCHER,
                    "name" => "update " . self::VOUCHER,
                    "group" => self::VOUCHER
                ],
                [
                    'key' => "add-" . self::VOUCHER . "-to",
                    'name' => "add " . self::VOUCHER . " to",
                    'group' => self::VOUCHER
                ],
                [
                    'key' => "validate" . self::VOUCHER . "voucher",
                    'name' => "validate " . self::VOUCHER,
                    'group' => self::VOUCHER
                ],
            ],
            self::PERMISSION => [
                [
                    'key' => "attach-" . self::PERMISSION,
                    'name' => "attach " . self::PERMISSION,
                    'group' => self::PERMISSION
                ],
                [
                    'key' => "detach-" . self::PERMISSION,
                    'name' => "detach " . self::PERMISSION,
                    'group' => self::PERMISSION
                ],
                [
                    'key' => "showAll-" . self::PERMISSION,
                    'name' => "showAll " . self::PERMISSION,
                    'group' => self::PERMISSION
                ],
                [
                    'key' => "show-" . self::PERMISSION,
                    'name' => "show " . self::PERMISSION,
                    'group' => self::PERMISSION
                ],
            ],
            self::ROLE => [
                [
                    'key' => "show-" . self::ROLE,
                    'name' => "show " . self::ROLE,
                    'group' => self::ROLE
                ],
                [
                    'key' => "showAll-" . self::ROLE,
                    'name' => "showAll " . self::ROLE,
                    'group' => self::ROLE
                ],
                [
                    'key' => "store-" . self::ROLE,
                    'name' => "store " . self::ROLE,
                    'group' => self::ROLE
                ],
                [
                    'key' => "destroy-" . self::ROLE,
                    'name' => "destroy " . self::ROLE,
                    'group' => self::ROLE
                ],
                [
                    'key' => "update-" . self::ROLE,
                    'name' => "update " . self::ROLE,
                    'group' => self::ROLE
                ],
            ],
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
