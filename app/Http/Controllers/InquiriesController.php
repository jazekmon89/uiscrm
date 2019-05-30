<?php

namespace App\Http\Controllers;

use Validator;

use App\Inquiry as Inquiry;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Flash;
use Socialite;

class InquiriesController extends Controller
{

    protected $redirectTo = "/";

    public function __construct()
    {
        $this->redirectTo = route("inquiries.create");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Inquiries.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $data = $request->all();  
        $validator = Validator::make($data, ['Description' => 'required']);

        if ($validator->fails())
        {
            Flash::error(trans("validation.custom.Description.required"));

            // redirect with errors for field error message if need
            // but since we flash the message no need to add error
            return redirect()->route('inquiries.create');
        }
        
        $inquery = new Inquiry($data);

        if (!empty($request->trigger))
        {
            // we store Inquiry to retrieved later on the callback
            // according to provider triggered
            session(['inquery' => $inquery]);

            if ($request->trigger == trans('messages.inquire_with_facebook')) 
            {   
                $provider = 'facebook';
            }
            else if ($request->trigger == trans('messages.inquire_with_google'))
            {
                $provider = 'google';
            }
            
            // overwrite provider redirect/callback before redirecting
            return Socialite::driver($provider)->redirectUrl(route("inquiries.with_$provider"))->redirect();
        }
        else if (Auth::check())
        {
            $is_auth = Auth::check();
            $user = Auth::user();
            $inquery->UserID = $is_auth ? $user->user_id : null;
            $inquery->InquirerName = $is_auth ? $user->first_name : null;
            $inquery->InquirerEmailAddress = $is_auth ? $user->email : null;

            if($inquery->SubmitInquiry())
            {
                Flash::success(trans('messages.inquery_submitted'));
            }

            return redirect($this->redirectTo);
        } 
        else 
        {   
            // this part is not neccessary
            Flash::error(trans("messages.inquery_login_error", ['login_link' => link_to_route('login', 'login')]));

            // retrieve input
            return redirect()->route('inquiries.create')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('Inquiries.form');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function withFB() 
    {
        return $this->inquireFormProvider('facebook');
    }

    public function withGoogle() 
    {
        return $this->inquireFormProvider('google');
    }

    private function inquireFormProvider($provider) 
    {
        $request = app('request');
                
        if (!$inquery = session('inquery')) 
        {
            Flash::success(trans("messages.inquery_invalid_request"));

            return redirect($this->redirectTo);
        }

        $user = Socialite::driver($provider)->redirectUrl(route("inquiries.with_$provider"))->user();
        $inquery->InquirerName = $user->name;
        $inquery->InquirerEmailAddress = $user->email;    

        if($result = $inquery->SubmitInquiry())
        {
            Flash::success(trans("messages.inquery_submitted"));
        }

        return redirect($this->redirectTo);
    }
}
