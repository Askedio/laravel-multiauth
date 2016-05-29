<?php

namespace App\MultiAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MultiAuth;

class MultiAuthController extends Controller
{
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $middleware = 'guest';

        if (app('auth')->check()) {
            $middleware = 'auth';
        }

        $this->middleware($middleware);
    }

    public function index(Request $request, $driver)
    {
        return MultiAuth::driver($driver)
                              ->callfront()
                              ->setPrevious(app('url')->previous())
                              ->setRedirect($this->redirectTo)
                              ->withResponse();
    }

    public function show(Request $request, $driver, $token = null)
    {
        if (!empty($token)) {
            $request->merge(['token' => $token]);
        }

        return MultiAuth::driver($driver)
                              ->callback()
                              ->setRedirect($this->redirectTo)
                              ->withResponse();
    }
}
