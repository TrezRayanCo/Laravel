<?php

namespace Trez\RayganSms;

use Illuminate\Support\ServiceProvider;

class RayganSmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('RayganSms', function ($app) {
            $conf = $app['config']['services.raygansms'];

            return new Sms($conf['user_name'], $conf['password'], $conf['phone_number']);
        });
    }
}
