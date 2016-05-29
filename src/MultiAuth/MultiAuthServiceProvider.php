<?php

namespace Askedio\MultiAuth;

use Askedio\MultiAuth\Contracts\Factory;
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

        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'multiauth');

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'multiauth');

        $this->publishes([
            __DIR__.'/../../resources/lang' => resource_path('lang/vendor/multiauth'),
            __DIR__.'/../../resources/views' => resource_path('views/vendor/multiauth'),
            __DIR__.'/../../config/multiauth.php' => config_path('multiauth.php'),
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Laravel\Socialite\SocialiteServiceProvider::class);

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Socialite', \Laravel\Socialite\Facades\Socialite::class);
        $loader->alias('MultiAuth', \Askedio\MultiAuth\Facades\MultiAuth::class);

        $this->app->singleton(Factory::class, function ($app) {
            return new MultiAuthManager($app);
        });

        $this->app->singleton('command.multiauth:deleteExpiredTokens', function () {
              return new Commands\DeleteExpiredOauthTokens();
        });

        $this->commands('command.multiauth:deleteExpiredTokens');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/multiauth.php',
            'multiauth'
        );
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
