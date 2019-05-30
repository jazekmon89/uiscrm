<?php

namespace App\Http\Controllers\Auth;

use Flash;
use Socialite;
use Validator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\User;

class SocialAuthController extends Controller
{

    public function __construct(){
        $this->middleware('guest');
    }

    /*
    |--------------------------------------------------------------------------
    | Redirects
    |--------------------------------------------------------------------------
    |
    | Here are the functions/redirects to OAuth or 3rd party login/register
    */
    public function facebookredirect() {
        return Socialite::driver('facebook')->scopes(['email','public_profile'])->redirectUrl(route('facebooklogincallback'))->redirect();
    }

    public function facebookregredirect() {
        return Socialite::driver('facebook')->scopes(['email','public_profile'])->redirectUrl(route('facebookregcallback'))->redirect();
    }

    public function googleredirect(Request $request){
        return Socialite::driver('google')->redirectUrl(route('googlelogincallback'))->redirect();
    }

    public function googleregredirect() {
        return Socialite::driver('google')->redirectUrl(route('googleregcallback'))->redirect();
    }

    /*
    |--------------------------------------------------------------------------
    | Login redirect callbacks
    |--------------------------------------------------------------------------
    |
    | Login callback
    */
    public function facebookcallback(Request $request)
    {
        return $this->logincallback('facebook');
    }

    public function googlecallback(Request $request)
    {
        return $this->logincallback('google');
    }

    private function loginCallback($provider)
    {
        $user = null;
        if($provider=="facebook")
            $user = Socialite::driver($provider)->scopes(['email','public_profile'])->redirectUrl(route("{$provider}logincallback"))->user();
        else
            $user = Socialite::driver($provider)->redirectUrl(route("{$provider}logincallback"))->user();
        $data = array(
            'first_name'    => $user->name,
            'last_name'     => '',
            'surname'     => '',
            'email_address' => $user->email
        );

        session(['user_initial_infos' => array_merge((array)session('user_initial_infos'), $data)]);

        $data['provider']   = $provider;
        $data['data']       = $user->getRaw();

        // for convenience we store data to Model User
        $data['email']      = $user->email;
        if(Auth::attempt($data, false, true)) {
            
            // Authentication passed...
            return redirect()->intended('/home/#');
        }else{
            //Flash::error(trans('messages.login_failed'));
            // redirect but no input snce we are using 3rd party login
            //return redirect()->route('login');
            return redirect()->route($provider.'reg');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Register redirect callbacks
    |--------------------------------------------------------------------------
    |
    | Register callback
    */
    public function facebookregcallback(Request $request)
    {
        return $this->registerCallback('facebook');
    }

    public function googleregcallback(Request $request){
        return $this->registerCallback('google');
    }

    private function registerCallback($provider)
    {
        $user = null;
        //$user = Socialite::driver($provider)->redirectUrl(route("{$provider}regcallback"))->user();
        if($provider=='facebook')
            $user = Socialite::driver($provider)->scopes(['public_profile','email'])->redirectUrl(route("{$provider}regcallback"))->user();
        else
            $user = Socialite::driver($provider)->redirectUrl(route("{$provider}regcallback"))->user();
        
        $user_name = property_exists($user, 'user') && isset($user->user['name'])?$user->user['name']:array();
        $data = array(
            'first_name'    => isset($user_name['givenName'])?$user_name['givenName']:$user->name,
            'last_name'     => isset($user_name['familyName'])?$user_name['familyName']:'',
            'surname'       => isset($user_name['familyName'])?$user_name['familyName']:'',
            'email_address' => $user->email,
            'oauth_provider'=> $provider, 
            'organisation'  => $this->getOrganizationID()
        );
        /*
        $data = array(
            'first_name'    => $user->name, 
            'email_address' => $user->email, 
            'oauth_provider'=> $provider, 
            'organisation'  => $this->getOrganizationID()
        );*/

        session(['user_initial_infos' => array_merge((array)session('user_initial_infos'), $data)]);
   
        return redirect("/register/#");
    }

    private function getOrganizationID() {
        // @NOTE: we should put this to config
        return 'de09f4b6-c708-4f5f-a48e-432af31e4d74';
    }
}