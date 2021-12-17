<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Session;
use JWTAuth;

class AuthApi {

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        if (isset($request->key) && $request->key == '$2y$10$aROSSAxEG7RgVYPL.f7VWOxWKIcly0ekxrNwc2h1Swktd1g0hl2/C') {
            return $next($request);
        }
        return response()->json(['code' => 500, 'message' => 'Api authentication failed!'], 200);
    }

}
