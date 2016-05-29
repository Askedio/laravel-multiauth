<?php

namespace App\MultiAuth\Providers;

use App\MultiAuth\Traits\AuthenticatesAndRegistersUsers;
use App\MultiAuth\Contracts\Provider as ProviderContract;
use App\MultiAuth\Traits\Errors;
use App\MultiAuth\Traits\Helpers;
use App\MultiAuth\Traits\Login;
use App\MultiAuth\Traits\Message;
use App\MultiAuth\Traits\Tokens;
use App\MultiAuth\Traits\Response;
use Illuminate\Http\Request;

abstract class BaseProvider implements ProviderContract
{
    use Message, Helpers, Tokens, Errors, Login, Response, AuthenticatesAndRegistersUsers;

    /**
     * [$guard description].
     *
     * @var string
     */
    protected $guard = 'multiAuth';

    /**
     * The HTTP request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Auth token.
     *
     * @var string
     */
    protected $config;

    /**
     * [$provider description].
     *
     * @var [type]
     */
    protected $provider;

    /**
     * [$message description].
     *
     * @var [type]
     */
    public $message;

    /**
     * [$redirectTo description]
     * @var string
     */
    public $redirectTo = '/';


    /**
     * Create a new provider instance.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $authToken
     *
     * @return void
     */
    public function __construct(Request $request, $config, $provider)
    {
        $this->request = $request;

        $this->config = $config;

        $this->provider = $provider;

        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    /**
     * [callfront description].
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function callfront()
    {
        //
    }

    /**
     * [callback description].
     *
     * @param Request $request [description]
     *
     * @return function [description]
     */
    public function callback()
    {
        $this->doLogin();

        return $this;
    }
}
