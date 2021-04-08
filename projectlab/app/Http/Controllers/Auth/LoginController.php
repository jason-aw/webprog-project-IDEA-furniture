<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override this function. In laravel, remember me function uses cookies that expires in 5 years, override cookie duration
     * 
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        // log in the user
        $result = Auth::attempt($this->credentials($request));

        // if login successful and remember me is checked
        if($request->has('remember') && $result){ 
            // get user
            $user = Auth::user();

            // ensure remember token is set, if not create new remember token
            if (empty($user->getRememberToken())) {
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
            
            // create cookie for 12 hours
            $customRememberMeTimeInMinutes = 12 * 60;  
            Cookie::queue(
                Cookie::make(Auth::getRecallerName(), $user->getAuthIdentifier().'|'.$user->getRememberToken().'|'.$user->getAuthPassword(), $minutes = $customRememberMeTimeInMinutes)
            );
        }

        return $result;
    }

}
