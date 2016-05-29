<?php

namespace Askedio\MultiAuth;

use Carbon\Carbon;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class MultiAuthEloquentProvider extends EloquentUserProvider implements UserProvider
{
    /**
     * [retrieveByEloquentCredentials description].
     *
     * @param array $credentials [description]
     *
     * @return [type] [description]
     */
    public function retrieveByEloquentCredentials(array $credentials)
    {
        return parent::retrieveByCredentials($credentials);
    }

    /**
     * [retrieveByCredentials description].
     *
     * @param array $credentials [description]
     *
     * @return [type] [description]
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (isset($credentials['password'])) {
            return $this->retrieveByEloquentCredentials($credentials);
        }

        return $this->createModel()->newQuery()->whereHas('oauth', function ($query) use ($credentials) {
            foreach ($credentials as $key => $value) {
                $query->where($key, $value);
            }

            return $query->where('expires_at', '>', Carbon::now());
        })->first();
    }

    /**
     * [validateCredentials description].
     *
     * @param Authenticatable $user        [description]
     * @param array           $credentials [description]
     *
     * @return [type] [description]
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (isset($credentials['password'])) {
            return parent::validateCredentials($user, $credentials);
        }

        // We only get a user if retrieveByCredentials found one, no validation needed.
        return !empty($user);
    }
}
