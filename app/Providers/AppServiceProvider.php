<?php

namespace App\Providers;

use App\Http\Controllers\api\v1\Payment\PaymentController;
use App\Services\Cart\CartStorage;
use App\Services\Cart\Contract\CartInterface;
use App\Services\Discount\Voucher;
use App\Services\Payment\Contract\PaymentInterface;
use App\Services\Payment\Lib\ZarinPayment;
use App\Services\Payment\Payment;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CartInterface::class, CartStorage::class);
        $this->app->bind('CartStorageService', CartStorage::class);
        $this->app->bind(PaymentInterface::class,Payment::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
