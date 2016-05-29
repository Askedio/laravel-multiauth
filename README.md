# Laravel Multi Auth
Authenticate your Laravel 5 application with email/password, email link, socialite and custom drivers.

![screen shot](http://i.imgur.com/NxiEQUM.png)

# Installation
#### Install with composer.
~~~
composer require askedio/laravel-multiauth:dev-master
~~~
#### Register the Provider in `config/app.php`
~~~
 Askedio\MultiAuth\MultiAuthServiceProvider::class,
~~~
#### Register the Alias in `config/app.php`
~~~
'MultiAuth' => Askedio\MultiAuth\Facades\MultiAuth::class,
~~~
#### Add to `app/Http/routes.php`
~~~
MultiAuth::route();
~~~~

#### Publish files & migrate
~~~
php artisan vendor:publish
php artisan migrate
~~~
#### Add to `app\User.php`
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

#### Modify `resources/views/auth/login.blade.php`
Include the multiauth login form.
~~~
@extends('layouts.app')

@section('content')
  @include('multiauth::login')
@endsection
~~~

#### Add to the `guards` array in `config/auth.php`
~~~
'multiAuth' => [
    'driver'   => 'session',
    'provider' => 'multiAuth',
],
~~~
#### Add to the `providers` array in `config/auth.php`
~~~
'multiAuth' => [
    'driver' => 'multiAuth',
    'model'  => App\User::class,
],
~~~
#### Add the services you want in `config/services.php`
~~~
'facebook' => [
    'client_id'     => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect'      => env('APP_URL').'/auth/facebook/callback',
],
~~~
#### Add the settings to `.env`
~~~
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
~~~


