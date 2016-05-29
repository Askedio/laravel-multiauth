<?php

namespace App\MultiAuth;

use App\MultiAuth\Contracts\Factory;
use Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class MultiAuthServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->provider('multiAuth', function ($app, array $config) {
            return new MultiAuthEloquentProvider($this->app['hash'], $config['model']);
        });

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('command.multiauth:deleteExpiredTokens')->hourly();
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new MultiAuthManager($app);
        });

        $this->app->singleton('command.multiauth:deleteExpiredTokens', function () {
              return new Commands\DeleteExpiredOauthTokens();
        });

        $this->commands('command.multiauth:deleteExpiredTokens');

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Factory::class];
    }
}
