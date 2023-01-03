<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1/')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        Route::prefix('product')->group(function () {
            Route::post('/create', [\App\Http\Controllers\api\V1\Product\ProductController::class, 'store'])
                ->middleware('can:store-product');
            Route::post('/update', [\App\Http\Controllers\api\V1\Product\ProductController::class, 'update'])
            ->middleware('can:update-product');
            Route::post('/select', [\App\Http\Controllers\api\V1\Product\ProductController::class, 'show'])
                ->middleware('can:show-product');
            Route::get('/selectAll', [\App\Http\Controllers\api\V1\Product\ProductController::class, 'showAll'])
                ->middleware('can:showAll-product');
            Route::post('/delete', [\App\Http\Controllers\api\V1\Product\ProductController::class, 'destroy'])
                ->middleware('can:destroy-product');
            Route::post('/status', [\App\Http\Controllers\api\V1\Product\ProductController::class, 'status'])
                ->middleware('can:status-product');
            Route::post('/likeProduct',[\App\Http\Controllers\api\V1\Product\ProductController::class,'likeProduct']);
        });

        Route::prefix('category')->group(function () {
            Route::post('/create', [\App\Http\Controllers\api\V1\Category\CategoryController::class, 'store'])
                ->middleware(['can:store-category','verified']);
            Route::post('/update', [\App\Http\Controllers\api\V1\Category\CategoryController::class, 'update'])
                ->middleware('can:update-category');
            Route::post('/select', [\App\Http\Controllers\api\V1\Category\CategoryController::class, 'show'])
                ->middleware('can:show-category');
            Route::get('/selectAll', [\App\Http\Controllers\api\V1\Category\CategoryController::class, 'showAll'])
                ->middleware('can:showAll-category');
            Route::post('/delete', [\App\Http\Controllers\api\V1\Category\CategoryController::class, 'destroy'])
                ->middleware('can:destroy-category');
            Route::post('/status', [\App\Http\Controllers\api\V1\Category\CategoryController::class, 'status'])
                ->middleware('can:status-category');
        });

        Route::prefix('brand')->group(function () {
            Route::post('/create', [\App\Http\Controllers\api\V1\Brand\BrandController::class, 'store'])
                ->middleware('can:store-brand');
            Route::post('/update', [\App\Http\Controllers\api\V1\Brand\BrandController::class, 'update'])
                ->middleware('can:update-brand');
            Route::post('/select', [\App\Http\Controllers\api\V1\Brand\BrandController::class, 'show'])
                ->middleware('can:show-brand');
            Route::get('/selectAll', [\App\Http\Controllers\api\V1\Brand\BrandController::class, 'showAll'])
                ->middleware('can:showAll-brand');
            Route::post('/delete', [\App\Http\Controllers\api\V1\Brand\BrandController::class, 'destroy'])
                ->middleware('can:destroy-brand');
            Route::post('/status', [\App\Http\Controllers\api\V1\Brand\BrandController::class, 'status'])
                ->middleware('can:status-brand');
        });

        Route::prefix('cart')->group(function () {
            Route::get('/list', [\App\Http\Controllers\api\V1\Cart\CartStorageController::class, 'showAll']);
            Route::post('/delete', [\App\Http\Controllers\api\V1\Cart\CartStorageController::class, 'destroy']);
            Route::post('/add', [\App\Http\Controllers\api\V1\Cart\CartStorageController::class, 'store']);
            Route::post('/update', [\App\Http\Controllers\api\V1\Cart\CartStorageController::class, 'update']);
            Route::get('/clear', [\App\Http\Controllers\api\V1\Cart\CartStorageController::class, 'destroyAll']);
        });

        Route::prefix('order')->group(function () {
            Route::post('/add', [\App\Http\Controllers\api\V1\Order\OrderController::class, 'store'])
                ->middleware('can:store-order');
            Route::post('/list', [\App\Http\Controllers\api\V1\Order\OrderController::class, 'show'])
                ->middleware('can:show-order');
            Route::post('/update', [\App\Http\Controllers\api\V1\Order\OrderController::class, 'update'])
                ->middleware('can:update-order');;
            Route::post('/delete', [\App\Http\Controllers\api\V1\Order\OrderController::class, 'destroy'])
                ->middleware('can:destroy-order');;
        });

        Route::prefix('voucher')->group(function () {
            Route::post('/add', [\App\Http\Controllers\api\V1\Voucher\VoucherController::class, 'store'])
                ->middleware('can:store-voucher');
            Route::post('/list', [\App\Http\Controllers\api\V1\Voucher\VoucherController::class, 'show'])
                ->middleware('can:show-voucher');
            Route::post('/update', [\App\Http\Controllers\api\V1\Voucher\VoucherController::class, 'update'])
                ->middleware('can:update-voucher');
            Route::post('/delete', [\App\Http\Controllers\api\V1\Voucher\VoucherController::class, 'destroy'])
                ->middleware('can:destroy-voucher');
            Route::post('/addVoucherTo', [\App\Http\Controllers\api\V1\Voucher\VoucherController::class, 'addVoucherTo'])
                ->middleware('can:add-voucher-to');
            Route::post('/validateVoucher', [\App\Http\Controllers\api\V1\Voucher\VoucherController::class, 'validateVoucher'])
                ->middleware('can:validate-voucher');
        });

        Route::prefix('favorite')->group(function (){
            Route::post('/add',[\App\Http\Controllers\api\V1\Favorite\FavoriteController::class,'store']);
            Route::post('/list',[\App\Http\Controllers\api\V1\Favorite\FavoriteController::class,'showAll']);
            Route::get('/detach-favorites',[\App\Http\Controllers\api\V1\Favorite\FavoriteController::class,'destroyAll']);
        });

        Route::prefix('comment')->group(function (){
            Route::post('/like-comment',[\App\Http\Controllers\api\V1\Comment\commentController::class,'likeComment']);
            Route::post('/add',[\App\Http\Controllers\api\V1\Comment\commentController::class,'store']);
            Route::post('/delete',[\App\Http\Controllers\api\V1\Comment\commentController::class,'destroy']);
            Route::post('/list',[\App\Http\Controllers\api\V1\Comment\commentController::class,'showAllProduct']);
            Route::post('/list-admin',[\App\Http\Controllers\api\V1\Comment\commentController::class,'showAll']);
            Route::post('/status',[\App\Http\Controllers\api\V1\Comment\commentController::class,'status']);
        });

        Route::prefix('permission')->group(function (){
            Route::post('/attach',[\App\Http\Controllers\api\V1\Permission\PermissionController::class,'attach'])
                ->middleware('can:attach-permission');
            Route::post('/list',[\App\Http\Controllers\api\V1\Permission\PermissionController::class,'showAll'])
                ->middleware('can:showAll-permission');
            Route::post('/detach',[\App\Http\Controllers\api\V1\Permission\PermissionController::class,'detach'])
                ->middleware('can:detach-permission');
            Route::post('/role-permission',[\App\Http\Controllers\api\V1\Permission\PermissionController::class ,'show'])
                ->middleware('can:show-permission');
        });


        Route::prefix('role')->group(function (){
            Route::post('/add',[\App\Http\Controllers\api\V1\Role\RoleController::class,'store'])
                ->middleware('can:store-role');
            Route::post('/delete',[\App\Http\Controllers\api\V1\Role\RoleController::class,'destroy'])
                ->middleware('can:destroy-role');
            Route::post('/update',[\App\Http\Controllers\api\V1\Role\RoleController::class,'update'])
                ->middleware('can:update-role');
            Route::get('/list',[\App\Http\Controllers\api\V1\Role\RoleController::class,'showAll'])
                ->middleware('can:showAll-role');
            Route::post('/role',[\App\Http\Controllers\api\V1\Role\RoleController::class,'show'])
                ->middleware('can:show-role');
        });

        Route::prefix('slider')->group(function (){
            Route::post('/add',[\App\Http\Controllers\api\V1\Slider\SliderController::class,'store']);
            Route::post('/update',[\App\Http\Controllers\api\V1\Slider\SliderController::class,'update']);
            Route::post('/delete',[\App\Http\Controllers\api\V1\Slider\SliderController::class,'destroy']);
            Route::get('/allSlide',[\App\Http\Controllers\api\V1\Slider\SliderController::class,'showAll']);
            Route::get('/slider',[\App\Http\Controllers\api\V1\Slider\SliderController::class,'show']);
        });

        Route::prefix('home')->group(function (){
            Route::get('/slider',[\App\Http\Controllers\api\V1\Slider\SliderController::class,'show']);
            Route::get('/categories',[\App\Http\Controllers\api\V1\Category\CategoryController::class,'showAllHome']);
            Route::get('/amazing',[\App\Http\Controllers\api\V1\Product\ProductController::class,'amazingProduct']);
            Route::get('/mostSales',[\App\Http\Controllers\api\V1\Product\ProductController::class,'mostSales']);
            Route::get('/newProducts',[\App\Http\Controllers\api\V1\Product\ProductController::class,'newProduct']);
            Route::get('/mostFavorite',[\App\Http\Controllers\api\V1\Product\ProductController::class,'mostFavorite']);
        });
    });

    Route::post('/payment', [\App\Http\Controllers\api\V1\Payment\PaymentController::class, 'payment']);
    Route::get('/verify', [\App\Http\Controllers\api\V1\Payment\PaymentController::class, 'verify'])->name('verify');


    Route::get('/logout', [\App\Http\Controllers\api\V1\Auth\AuthController::class, 'logout']);
    Route::get('/verify-email', [\App\Http\Controllers\api\V1\Auth\AuthController::class, 'verifyEmail']);
    Route::post('/login', [\App\Http\Controllers\api\V1\Auth\AuthController::class, 'login'])->name('login');
    Route::post('/register', [\App\Http\Controllers\api\V1\Auth\AuthController::class, 'register']);
    Route::get('/verify/code',[\App\Http\Controllers\api\V1\Auth\AuthController::class,'verify']);
});


