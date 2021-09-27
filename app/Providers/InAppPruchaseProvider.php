<?php

namespace App\Providers;

use App\Services\InAppPruchase;
use Illuminate\Support\ServiceProvider;

class InAppPruchaseProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(InAppPruchase::class, function () {
            return new InAppPruchase(request());
        });
    }

    public function boot()
    {
        //
    }
}
