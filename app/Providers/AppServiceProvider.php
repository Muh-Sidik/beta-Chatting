<?php

namespace App\Providers;

use App\DetailChat;
use App\Observers\ChatObserver;
use Illuminate\Support\ServiceProvider;
use  Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class LaravelLoggerProxy {
    public function log( $msg ) {
        Log::info($msg);
    }
}

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $pusher = $this->app->make('pusher');
        // $pusher->set_longger(new LaravelLoggerProxy());
    }
}
