<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Log;
use DB;
use Flash;
use Session;
use Socialite;
use App\Http\Controllers\Auth\SocialAuthController;
use DateTime;
use Mail;
use App\Mail\RegistrationNotification;
use Illuminate\Support\Facades\Auth;

class InsuranceInterfaceController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    public function searchForm(Request $request, User $user){
        //$this->authorize('canClientAccess', $user);
        return view("uis.InsuranceInterface.search.form");
    }
}
