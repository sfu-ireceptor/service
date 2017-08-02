<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

// just so Laravel uses "username" instead of "email"
class AuthenticateWithBasicAuth
{
    protected $auth;

    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $guard = null)
    {
        return $this->auth->guard($guard)->basic('username') ?: $next($request);
    }
}
