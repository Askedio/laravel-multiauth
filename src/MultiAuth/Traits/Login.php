<?php

namespace App\MultiAuth\Traits;

use App\MultiAuth\Jobs\SendEmailLoginToken;
use Illuminate\Http\Request;

trait Login
{
    /**
     * [doLogin description].
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    protected function doLogin()
    {
        try {
            $this->login($this->request);
        } catch (\Exception $e) {
            $this->setError($this->authenticationError($e->getMessage()));
        }

        return app('auth')->guard($this->getGuard())->user();
    }

    /**
     * [sendLoginLink description]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    protected function sendLoginLink($user)
    {
        $oauth = $this->createAuthToken($user);

        dispatch(new SendEmailLoginToken($user, $oauth));

        return $this->setSuccess($user, 'emailed', ['oauth' => $oauth]);
    }
}
