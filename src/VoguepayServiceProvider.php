<?php
/**
 * Created by PhpStorm.
 * User: Kingsley
 * Date: 15/06/2018
 * Time: 4:28 PM
 */

namespace Kingsley\Voguepay;

use Illuminate\Support\ServiceProvider;


class VoguepayServiceProvider extends ServiceProvider
{
    /**
     * Publishes all the config file this package needs to function
     */
    public function boot()
    {
        $config = dirname(__DIR__) . '/config/voguepay.php';
        $this->publishes([
            $config => config_path('voguepay.php')
        ]);
    }

    /*
     * Register the application services.
     **/
    public function register()
    {
        $this->app->bind('voguepay-laravel', function () {
            return new Voguepay;
        });
    }

    /**
     * Get the services provided by the provider
     * @return array
     */
    public function provides()
    {
        return ['voguepay-laravel'];
    }
}