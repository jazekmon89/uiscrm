<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Flash;

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
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @param  string state
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $state = ''){
        return Validator::make($data, [
            'username' => 'required|max:255',
            //'password' => 'required|min:6|confirmed',
            'password' => 'required|max:255',
        ]);
    }

    public function login(Request $request){
        $data = $request->all();
        $this->validator($data)->validate();
        
        // TODO: authenticate the user!
        //$res = $this->User_AuthenticateByUsernameAndPassword($data, $request);

        //$data['data']       = $user->getRaw();

        // for convenience we store data to Model User
        //$data['email']      = $user->email;
        if(Auth::attempt($data, false, true)) {
            
            // Authentication passed...
            return redirect()->intended('/home/#');
        }else{
            Flash::error(trans('messages.login_failed'));
            return back();
            //return redirect()->route('login');
            //return redirect()->route($provider.'reg');
        }


        if($res === 0){ // error, should stay on the current page

        }else{
            return redirect("admin-login");
        }
    }

    public function showLoginForm(){
        return view('auth.login.login');
    }

    public function showAdminLoginForm(){
        return view('auth.login.admin');
    }

    public function logout(){
        Auth::logout();
        Session::flush();
        return redirect('/');
    }
}
