<?php

namespace App\Auth\Passwords;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Passwords\PasswordResetServiceProvider;

class MyPasswordResetServiceProvider extends PasswordResetServiceProvider
{

    /**
     * Register the password broker instance.
     *
     * @return void
     */
    protected function registerPasswordBroker()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new MyPasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }

}
