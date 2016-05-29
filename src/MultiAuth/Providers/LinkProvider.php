<?php

namespace Askedio\MultiAuth\Providers;

use Illuminate\Http\Request;

class LinkProvider extends BaseProvider
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
            'email' => $data[config('multiauth.link.field')],
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
            'token' => $request->input('token'),
            'type'  => 'email',
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

        if (!$user = $this->retrieveByEloquentCredentials($this->createData($this->request->all()))) {
            $user = $this->create($this->request->all());

            if (!config('multiauth.email.confirm')) {
                return $this->authenticated($this->request, $user);
            }
        }

        return $this->sendLoginLink($user);
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
        if ($user = $this->doLogin()) {
            $user->oauth()->where('token', $this->request->input('token'))->delete();

            if ($user->confirmed == 0 && config('multiauth.email.confirm')) {
                $user->update([
                  'confirmed' => true,
                ]);
            }
        }

        return $this;
    }
}
