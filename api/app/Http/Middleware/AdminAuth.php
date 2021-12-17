<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Session;

class AdminAuth
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $headers = getallheaders();
        $session = $request->session()->get('admin');
        if (isset($headers['Authorization']))
        {
            if (str_replace('Bearer ', '', $headers['Authorization']) == $session['token'])
            {
                return $next($request);
            }
        }
        return response()->json('Unauthorized', 401);
    }

}
