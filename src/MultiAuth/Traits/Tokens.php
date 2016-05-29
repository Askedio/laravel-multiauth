<?php

namespace App\MultiAuth\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait Tokens
{
    /**
       * [createAuthToken description].
       *
       * @param [type] $user    [description]
       * @param [type] $request [description]
       *
       * @return [type] [description]
       */
      protected function createAuthToken($user)
      {
          $user->oauth()->whereType($this->provider)->delete();

          return $user->oauth()->create(
              $this->authTokenData(
                  $this->request->input()
              )
          );
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
              'type'       => 'email',
              'token'      => str_random(12),
              'expires_at' => Carbon::parse($this->getRules('expires')),
          ];
      }
}
