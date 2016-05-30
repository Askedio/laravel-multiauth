# Laravel Multi Auth
Authenticate your Laravel 5 application with email/password, email link, socialite and custom drivers. [Demo](https://cruddy.io/apps/multiauth/login) & [Example app](https://github.com/Askedio/laravel-multi-oauth).


![screen shot](http://i.imgur.com/NxiEQUM.png)

# Installation
## Install with composer.
~~~
composer require askedio/laravel-multiauth
~~~

## Enable
Edit `config/app.php`

Register the `provider` and `alias`
~~~
 Askedio\MultiAuth\MultiAuthServiceProvider::class,
~~~
~~~
'MultiAuth' => Askedio\MultiAuth\Facades\MultiAuth::class,
~~~

## Publish & migrate
Please note: The `users` table needs some [new fields](https://github.com/Askedio/laravel-multiauth/blob/master/database/migrations/2014_10_12_000000_add_users_table.php) & a [table](https://github.com/Askedio/laravel-multiauth/blob/master/database/migrations/2016_05_24_000000_create_user_oauth_table.php) for the tokens.
~~~
php artisan vendor:publish
php artisan migrate
~~~

## Configure Routes
Edit `app/Http/routes.php`

Add the following line.
~~~
MultiAuth::route();
~~~~

## Configure User Model
Edit `app\User.php`

Change fillable to read.
~~~
protected $fillable = [
      'name', 'email', 'password', 'avatar', 'nickname', 'confirmed',
];
~~~
Add the oauth relation.
~~~
public function oauth()
{
    return $this->hasMany(\Askedio\MultiAuth\Models\UserOauth::class);
}
~~~

## Configure Auth Guards & Providers
Edit `config/auth.php`


Add to the `guards` array
~~~
'multiAuth' => [
    'driver'   => 'session',
    'provider' => 'multiAuth',
],
~~~

Add to the `providers` array
~~~
'multiAuth' => [
    'driver' => 'multiAuth',
    'model'  => App\User::class,
],
~~~
## Configure your template
Edit `resources/views/auth/login.blade.php`

Replace content with the `multiauth::login` login form.
~~~
@extends('layouts.app')

@section('content')
  @include('multiauth::login')
@endsection
~~~

## Configure Socialite
Edit `config/services.php`

Add all the services you want.
~~~
'facebook' => [
    'client_id'     => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect'      => env('APP_URL').'/auth/facebook/callback',
],
~~~
Edit `.env` and add all the settings.
~~~
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
~~~

