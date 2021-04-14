<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    //-----------------------------------------------------------------------------
    // *** WARNING ***  The functionality in this LoginController works
    //                  because we are overriding functions within Traits
    //

    /*
    |------------------------------------------------------------------------------
    | Login Controller
    |------------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
 
    use AuthenticatesUsers;

    public function attemptLogin(Request $request)
    {
        //-------------------------------------------------------------------------
        //  Attempt to issue a token to the user based on the login credentials
        //
        $token = $this->guard()->attempt($this->credentials($request));
        
        //  If no token you cant go through
        //
        if(! $token){
            return false;
        }

        //  If there is a token Get the autheticated user
        //
        $user = $this->guard()->user();

        //  If the user at this point has not verified their email they cant go on
        //
        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return false;
        }

        //  Set the user token
        //
        $this->guard()->setToken($token);

        return true;
        //-------------------------------------------------------------------------
    }


    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        //  Get the token from the authentication guard ( JWT )
        //
        $token = (string)$this->guard()->getToken();

        //  Extract the expiry date of the token
        //
        $expiration = $this->guard()->getPayload()->get('exp');
    
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ]);        
    }


    protected function sendFailedLoginResponse()
    {
        $user = $this->guard()->user();

        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "verification" => "You need to verify your email account"
            ]], 422);
        }

        throw ValidationException::withMessages([
            $this->username() => "Authentication failed invalid credentials"
        ]);
    }


    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['message' => 'Logged out successfully!']);
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //  protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //  public function __construct()
    //  {
    //      $this->middleware('guest')->except('logout');
    //  }
}
