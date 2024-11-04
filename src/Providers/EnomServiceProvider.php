<?php

namespace Coreproc\Enom\Providers;

use Coreproc\Enom\Domain;
use Coreproc\Enom\Enom;
use Coreproc\Enom\Tld;
use Coreproc\Enom\Transfer;
use Illuminate\Support\ServiceProvider;

class EnomServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '..\..\config\enom.php' => config_path('enom.php')
        ]);
    }

    public function register()
    {
        $enom = new Enom(config('enom.userId'), config('enom.password'));

        $this->app->bind('tld', function () use ($enom) {
            return new Tld($enom);
        });

        $this->app->bind('domain', function () use ($enom) {
            return new Domain($enom);
        });

        $this->app->bind('transfer', function () use ($enom) {
            return new Transfer($enom);
        });
    }

}