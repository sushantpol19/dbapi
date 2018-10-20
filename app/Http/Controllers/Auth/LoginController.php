<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/*Libraries*/
use Illuminate\Http\Request;

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
    protected $redirectTo = '/home';

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
     * To custom login a user
     *
     * @ 
     */
    public function login(Request $request)
    {
    	$this->validateLogin($request);

    	if($this->attemptLogin($request))
    	{
    		$user = $this->guard()->user();
    		$user->generateToken();

    		return response()->json([
    			'data'					=>	$user->toArray()
    		]);
    	}

    	return $this->sendFailedLoginResponse($request);
    }

    /**
     * When the user is logging out
     *
     * @ 
     */
    public function logout(Request $request)
    {
    	$user = \Auth::guard('api')->user();

    	if($user)
    	{
    		$user->api_token = null;
    		$user->save();

    		return response()->json([
    			'data'	=>	'User logged out successfully'
    		], 200);
    	}

    	return response()->json([
    		'data'	=>	'No user logged in'
    	], 204);
    }
}
