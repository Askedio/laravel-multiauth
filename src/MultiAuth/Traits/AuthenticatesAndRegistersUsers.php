<?php

namespace App\MultiAuth\Traits;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Validator;

trait AuthenticatesAndRegistersUsers
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins, ValidatesRequests;

    /**
     * [validateLogin description].
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function validateLogin(Request $request)
    {
        $this->validate($request, $this->getRules('login'));
    }

    /**
     * [handleUserWasAuthenticated description].
     *
     * @param Request $request   [description]
     * @param [type]  $throttles [description]
     *
     * @return [type] [description]
     */
    public function handleUserWasAuthenticated(Request $request, $throttles)
    {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        $this->authenticated($request, app('auth')->guard($this->getGuard())->user());
    }

    /**
     * [sendFailedLoginResponse description].
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function sendFailedLoginResponse(Request $request)
    {
        return $this->setError($this->authenticationError($this->getFailedLoginMessage()));
    }

    /**
     * [sendLockoutResponse description].
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function sendLockoutResponse(Request $request)
    {
        $seconds = $this->secondsRemainingOnLockout($request);

        return $this->setError($this->authenticationError($this->getLockoutErrorMessage($seconds)));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return app('validator')->make($data, $this->getRules('register'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    public function create(array $data)
    {
        $model = app('auth')->guard($this->getGuard())->getProvider()->getModel();

        return $model::create($this->createData($data));
    }

    /**
     * [authenticated description]
     * @param  Request $request [description]
     * @param  [type]  $user    [description]
     * @return [type]           [description]
     */
    public function authenticated(Request $request, $user)
    {
        // Login to the default guard.
        app('auth')->login($user, true);

        return $this->setSuccess($user, 'authenticated');
    }
}
