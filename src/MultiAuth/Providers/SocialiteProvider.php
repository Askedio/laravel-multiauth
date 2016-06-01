<?php

namespace Askedio\MultiAuth\Providers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Socialite;

class SocialiteProvider extends BaseProvider
{
    /**
     * [$socialite description]
     * @var [type]
     */
    protected $socialite;

    /**
     * [init description]
     * @return [type] [description]
     */
    protected function init()
    {
        $this->checkIfDriverExists($this->provider);

        $this->socialite = Socialite::driver($this->provider);
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
        return config('multiauth.socialite.'.$rule);
    }

    /**
     * [createData description].
     *
     * @param [type] $data [description]
     *
     * @return [type] [description]
     */
    protected function createData($data)
    {
        return [
            'email'    => $data['email'],
            'name'     => $data['name'],
            'nickname' => $data['nickname'],
            'avatar'   => $data['avatar'],
        ];
    }

    /**
     * [authTokenData description].
     *
     * @param [type] $data [description]
     *
     * @return [type] [description]
     */
    protected function authTokenData($data)
    {
        return [
            'type'         => $this->provider,
            'expires_at'   => $data['expires_at'],
            'social_id'    => $data['social_id'],
            'token'        => $data['token'],
            'token_secret' => $data['token_secret'],
        ];
    }

    /**
     * [getCredentials description].
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    protected function getCredentials(Request $request)
    {
        return [
            'social_id'    => $request->input('social_id'),
            'type'         => $this->provider,
        ];
    }

    /**
     * [updateRequest description]
     * @param  [type] $socialiteUser [description]
     * @return [type]                [description]
     */
    protected function updateRequest($socialiteUser)
    {
        $this->request->merge([
            'social_id'    => $socialiteUser->getId(),
            'email'        => $socialiteUser->getEmail(),
            'name'         => $socialiteUser->getName(),
            'avatar'       => $socialiteUser->getAvatar(),
            'nickname'     => $socialiteUser->getNickname(),
            'token'        => $socialiteUser->token,
            'token_secret' => method_exists($socialiteUser, 'tokenSecret') ? $socialiteUser->tokenSecret : null,
            'expires_at'   => method_exists($socialiteUser, 'expiresIn') ? $socialiteUser->expiresIn : Carbon::parse(config('multiauth.socialite.expires')),
        ]);

        return $socialiteUser;
    }

    /**
     * [getSocialiteUserByRequest description]
     * @return [type] [description]
     */
    protected function getSocialiteUserByRequest()
    {
        if ($this->request->ajax() || $this->request->wantsJson()) {
            return $this->socialite->userFromToken($this->request->input('token'));
        }

        return $this->socialite->user();
    }

    /**
     * [isValid description]
     * @return boolean [description]
     */
    private function isValid()
    {
        $socialiteUser = false;

        try {
            $socialiteUser = $this->getSocialiteUserByRequest();
        } catch (\Exception $e) {
            //
        }

        if ($socialiteUser) {
            return $this->updateRequest($socialiteUser);
        }

        $this->sendFailedLoginResponse($this->request);

        return false;
    }

    /**
     * [callback description]
     * @return function [description]
     */
    public function callback()
    {
        if (!$this->isValid()) {
            return $this;
        }

        if ($this->hasValidationErrors()) {
            return $this;
        }

        if ($user = $this->getCurrentUser()) {
            if ($this->isExistingUser()) {
                $this->setError($this->authenticationError(trans('multiauth::multiauth.existing', ['provider' => $this->provider])));

                return $this;
            }
        }

        if (!$user) {
            if (!$user = $this->doLogin()) {
                if (!$user = $this->retrieveByEloquentCredentials([
                    'email' => $this->request->input('email'),
                ])) {
                    $user = $this->create($this->request->all());
                }
            }
        }

        $this->createAuthToken($user);

        return $this->authenticated($this->request, $user);
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
        $scopes = config(sprintf('services.%s.scopes', $this->provider));
        if (!empty($scopes)) {
            $this->socialite->scopes($scopes);
        }

        try {
            $redirect = $this->socialite->redirect()->getTargetUrl();
        } catch (\Exception $e) {
            abort(404);
        }

        return $this->setSuccess(false, 'redirect', ['url' => $redirect]);
    }

    /**
     * Check if a driver exists, if it doesnt return a redirect, if it does return false.
     *
     * @param string $driver
     *
     * @return mixed
     */
    private function checkIfDriverExists($driver)
    {
        if (!config(sprintf('services.%s.%s', $this->provider, 'client_id'))) {
            abort(404);
        }
    }
}
