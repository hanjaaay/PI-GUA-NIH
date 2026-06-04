<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SimpleSoftwareIO\QrCode\QrCodeServiceProvider as BaseQrCodeServiceProvider;

class QrCodeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(BaseQrCodeServiceProvider::class);
    }

    public function boot()
    {
        //
    }
}
