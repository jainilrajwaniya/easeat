<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Auth\Factory as Auth;
use Closure;
use App\Models\GuestUsers;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
//    protected function redirectTo($request)
//    {
//        if (! $request->expectsJson()) {
//            $response = [
//                'status' => false,
//                'message' => 'Unauthorized',
//                'message_code' => 'UNAUTHORIZED',
//                'status_code' => 401
//            ];
//
//            return response()->json($response, 401);
//            return route('login');
//        }
//    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($guard == 'api') {
            $guestUser = 0;
            if ($this->auth->guard($guard)->guest()) {
                if(isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION']) {
                    $authArr = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
                    $token = (!empty($authArr[1]) ? $authArr[1] : '');
                    $guestUser = GuestUsers::where(['device_token' => $token])->first();
                }
                if($guestUser) {
                    return $next($request);
                } else {
                    $response = [
                        'status' => false,
                        'message' => 'Unauthorized',
                        'message_code' => 'UNAUTHORIZED',
                        'status_code' => 401
                    ];

                    return response()->json($response, 401);
                }
            } else {
                return $next($request);
            }
        } else {
            if ($this->auth->guard($guard)->guest()) {
	        return redirect($guard.'/login');
	    } else {
                return $next($request);
            }
        }
    }
}
