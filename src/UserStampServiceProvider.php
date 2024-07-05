<?php

namespace Jcrodsolutions\LaravelUserStamp;

use Illuminate\Support\ServiceProvider;

class UserStampServiceProvider extends ServiceProvider{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        //
    }

    public function boot(){
        $this->publishes([
            __DIR__ . '/../config/user-stamp.php' => config_path(path: 'user-stamp.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../config/user-stamp.php', 'user-stamp'
        );
    }
}