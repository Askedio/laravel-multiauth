<?php

namespace App\MultiAuth;

use Illuminate\Support\Manager;
use InvalidArgumentException;
use Route;

class MultiAuthManager extends Manager implements Contracts\Factory
{
    /**
     * [$driver description].
     *
     * @var [type]
     */
    protected $driver;


    public function route($base = 'auth', $back = 'callback')
    {
        $base = sprintf('%s/{driver}', $base);
        $back = sprintf('%s/%s', $base, $back);

        Route::get($base, '\App\MultiAuth\Controllers\MultiAuthController@index');
        Route::post($base, '\App\MultiAuth\Controllers\MultiAuthController@index');
        Route::get($back.'/{token}', '\App\MultiAuth\Controllers\MultiAuthController@show');
        Route::get($back, '\App\MultiAuth\Controllers\MultiAuthController@show');
    }

    /**
     * [createLinkDriver description].
     *
     * @return [type] [description]
     */
    protected function createLinkDriver()
    {
        $config = $this->app['config'];

        return $this->buildProvider(\App\MultiAuth\Providers\LinkProvider::class, $config);
    }

    /**
     * [createSocialiteDriver description].
     *
     * @return [type] [description]
     */
    protected function createSocialiteDriver()
    {
        $config = $this->app['config'];

        return $this->buildProvider(\App\MultiAuth\Providers\SocialiteProvider::class, $config);
    }

    /**
     * [createEmailDriver description].
     *
     * @return [type] [description]
     */
    protected function createEmailDriver()
    {
        $config = $this->app['config'];

        return $this->buildProvider(\App\MultiAuth\Providers\EmailProvider::class, $config);
    }

    /**
     * Get a driver instance.
     *
     * @param string $driver
     *
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

    /**
     * Build a provider instance.
     *
     * @param string $provider
     * @param array  $config
     *
     * @return
     */
    public function buildProvider($provider, $config)
    {
        return new $provider($this->app['request'], $config, $this->driver);
    }

    /**
     * Get a driver instance.
     *
     * @param string $driver
     *
     * @return mixed
     */
    public function driver($driver = null)
    {
        $driver = $driver ?: $this->getDefaultDriver();

        $this->driver = $driver;

        if (!in_array($driver, config('multiauth.drivers'))) {
            $driver = 'socialite';
        }

        return parent::driver($driver);
    }

    /**
     * Get the default driver name.
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No multi auth driver was specified.');
    }
}
