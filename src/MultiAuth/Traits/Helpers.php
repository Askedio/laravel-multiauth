<?php

namespace App\MultiAuth\Traits;

use Illuminate\Http\Request;

trait Helpers
{
    /**
     * [currentUser description].
     *
     * @return [type] [description]
     */
    protected function getCurrentUser()
    {
        return app('auth')->user();
    }

    /**
     * [retrieveByEloquentCredentials description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    protected function retrieveByEloquentCredentials($data)
    {
        return $this->getProvider()->retrieveByEloquentCredentials($data);
    }

    /**
     * [retrieveByAuthCredentials description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    protected function retrieveByAuthCredentials($data)
    {
        return $this->getProvider()->retrieveByCredentials($data);
    }

    /**
     * [isExistingUser description]
     * @return boolean [description]
     */
    protected function isExistingUser()
    {
        if ($this->retrieveByAuthCredentials($this->getCredentials($this->request))) {
            return true;
        }

        return false;
    }

    /**
     * [getProvider description].
     *
     * @return [type] [description]
     */
    protected function getProvider()
    {
        return app('auth')->guard($this->getGuard())->getProvider();
    }

    /**
     * [getRules description].
     *
     * @param [type] $rule [description]
     *
     * @return [type] [description]
     */
    protected function getRules($rule)
    {
        return config(sprintf('multiauth.%s.%s', $this->provider, $rule));
    }


    /**
     * [setPrevious description]
     * @param [type] $redirectToOnError [description]
     */
    public function setPrevious($redirectToOnError) {
        $redirectToOnError = $this->request->session()->flash('redirectToOnError', $redirectToOnError);

        return $this;
    }

    /**
     * [setRedirect description]
     * @param [type] $redirectTo [description]
     */
    public function setRedirect($redirectTo)
    {
        $this->redirectTo = $redirectTo;

        return $this;
    }

    /**
     * [isAnApi description]
     * @return boolean [description]
     */
    protected function isAnApi()
    {
        return $this->request->ajax() || $this->request->wantsJson();
    }
}
