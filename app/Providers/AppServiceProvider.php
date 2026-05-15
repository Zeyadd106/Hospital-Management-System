<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;
use App\Channels\CustomDatabaseChannel;
use App\Models\CustomNotification;
use Illuminate\Notifications\DatabaseNotification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            BaseDatabaseChannel::class,
            CustomDatabaseChannel::class
        );

        $this->app->bind(
            DatabaseNotification::class,
            CustomNotification::class
        );
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
