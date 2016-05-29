<?php

namespace Askedio\MultiAuth\Providers;

use Illuminate\Http\Request;

class EmailProvider extends BaseProvider
{
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
            'email'     => $data['email'],
            'password'  => bcrypt($data['password']),
            'confirmed' => !config('multiauth.email.confirm'),
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
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ];
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
        if ($this->hasValidationErrors()) {
            return $this;
        }

        if (!$user = $this->retrieveByEloquentCredentials([
                'email'    => $this->request->input('email'),
                'password' => bcrypt($this->request->input('password')),
        ])) {
            $confirm = config('multiauth.email.confirm');

            $user = $this->createUser($this->request->all(), $confirm);

            if ($confirm) {
                return $this->sendLoginLink($user);
            }

            return $this->authenticated($this->request, $user);
        }

        if ($user->confirmed == 0) {
            return $this->setError($this->authenticationError(trans('multiauth.confirmation')));
        }

        return $this->callback();
    }

    /**
     * [createUser description]
     * @param  [type] $data    [description]
     * @param  [type] $confirm [description]
     * @return [type]          [description]
     */
    private function createUser($data, $confirm)
    {
        if ($confirm) {
            $data = array_merge($data, ['confirmed' => false]);
        }

        return $this->create($data);
    }
}
