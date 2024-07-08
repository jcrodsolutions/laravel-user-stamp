<?php

namespace Jcrodsolutions\LaravelUserStamp;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class UserStampServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/user-stamp.php' => config_path(path: 'user-stamp.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/../config/user-stamp.php', 'user-stamp');

        /*
         * This is for using $table->ato(); in any migration.
         *
         */
        Blueprint::macro('ato', function () {
            $class = config('auth.providers.users.model');
            $usersTable = (new $class())->getTable();

            $strActivo = config('user-stamp.active', 'active');
            $strCreadoPor = config('user-stamp.created_by', 'created_by');
            $strActualizadoPor = config('user-stamp.updated_by', 'updated_by');

            $this->boolean($strActivo)->default(1)->nullable(false);
            $this->foreignId($strCreadoPor)->constrained($usersTable)->nullable(false);
            $this->foreignId($strActualizadoPor)->constrained($usersTable)->nullable(false);
        });
    }
}
