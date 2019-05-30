<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Log;
use Flash;
use Session;
use Socialite;
use App\Http\Controllers\Auth\SocialAuthController;
use DateTime;
use Mail;
use App\Mail\RegistrationNotification;
use Illuminate\Support\Facades\Auth;
use App\StoredProcTrait as StoredProc;
use App\Register as Register;
use App\Helpers\OrganisationHelper as OrganisationHelper;
//use App\Policy;

class RegisterController extends Controller
{
    use StoredProc;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    //use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/register-preconfirm', $register = null, $policy;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware(function ($request, $next){
            view()->share('all_details', $request->session()->get('user_initial_infos'));
            return $next($request);
        });
        $this->register = new Register();
        //$this->policy = new Policy;
    }    

    private function getStates($CountryName = 'Australia'){
        $states_raw = $this->register->State_GetStatesByCountryName($CountryName); // TODO: this should be dynamic.
        $states = [''=>'Please select.'];
        foreach($states_raw as $i){
            $states[$i->StateShortName] = $i->StateLongName;
        }
        return $states;
    }

    public function registerSelection(Request $request){
        return view("auth.register.register-selection");
    }

    public function register(Request $request){
        $this->allowAccess($request, 1);
        return view("auth.register.register", ['all_details'=>$request->session()->get('user_initial_infos'), 'state_options'=>$this->getStates()]);
    }

    public function registerProfile(Request $request){
        if(!$this->canAccess($request, 2))
            return back();
        $this->allowAccess($request, 2);
        $old_details = $request->session()->get('user_initial_infos');
        view()->share('all_details', $old_details);
        return view('auth.register.register-profile');
    }

    /**
     * View the new user address page
     *
     * @return \Illuminate\Http\Response
     */
    public function createAddressesPage(Request $request){
        if(!$this->canAccess($request, 3))
            return back();
        $this->allowAccess($request, 3);
        $old_details = $request->session()->get('user_initial_infos');
        return view('auth.register.register-full-address-form', ['all_details'=>$old_details, 'state_options'=>$this->getStates()]);
    }

    public function confirmationEmail(Request $request){
        if(!$this->canAccess($request, 4))
            return back();
        Session:flush();
        $this->allowAccess($request, 4);
        return view('auth.register.email-notification');
    }

    /**
     * Redirect to temporaray password page.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function temporaryPasswordEmailSent(Request $request){
        return view('auth.register.temp-pass-email');
    }

    private function allowAccess(Request $request, $step){
        $session = $request->session();
        $reg_step = $session->has('registration_process')?intval($session->get('registration_process')):null;
        if(empty($reg_step) || (!empty($reg_step) && $step < $reg_step))
            $request->session()->put('registration_process', $step);
    }

    private function removeAccess(Request $request){
        $request->session()->forget('registration_process');
    }

    private function canAccess(Request $request, $step){
        $session = $request->session();
        $reg_step = $session->has('registration_process')?intval($session->get('registration_process')):null;
        if(empty($reg_step) || (!empty($reg_step) && $step < $reg_step)){
            Flash::error(trans('messages.page_access_restricted'));
            return false;
        }
        return true;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @param  string state
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data){
        $validations = array (
            'date_of_birth' => 'required|date_format:"d/m/Y"|before:18 years ago',
            //'unit_number' => 'max:20',
            //'street_number' => 'required|max:20',
            //'street_name' => 'required|max:100',
            'address_line_1' => 'required|min:1',
            //'city' => 'required|max:50',
            'state' => 'required|max:40',
            'first_name' => 'required|max:50',
            'middle_name' => 'max:50',
            'surname' => 'required|max:50',
            'preferred_name' => 'max:50',
            //'birth_country' => 'required|max:100',
            'country' => 'required|max:100',
            'birth_city' => 'required|max:100',
            'town_or_suburb' => 'required|max:50',
            'post_code' => 'required|regex:/^[0-9]{4}(\-[0-9]{4})?$/',
            'email_address' => 'required|email|max:250',
            'mobile_phone_number' => 'regex:/[0-9]/|max:20',
            'length_of_time' => 'required|regex:/[0-1]{1}/|max:1',
            'prev_home_from_date' =>'required|date_format:"d/m/Y"|after:'.(isset($data['date_of_birth'])?$data['date_of_birth']:'01/01/2016').'|before:'.date('d/m/Y', strtotime('+1 day')),
            'prev_home_to_date' =>'required|date_format:"d/m/Y"|after:{prev_home_from_date}|before:'.date('d/m/Y', strtotime('+1 day')),
        );
        $placeholders = array(
            'prev_home_from_date'=>'prev_home_to_date',
        );
        $rules = array(
            'before' => trans('date.dob_not_allowed',['min'=>'18']),
        );
        $prev_rules = array(
            'before' => trans('date.date_in_future'),
        );
        $to_validate = array();
        foreach($data as $k=>$i){
            foreach($validations as $h=>$j){
                if(strpos($k, $h) !== false){
                    $to_validate[$k] = $j;
                    break;
                }
            }
        }
        $prev_flag = false;
        foreach($placeholders as $k=>$i){
            foreach($data as $h=>$j){
                if(strpos($h, $k) !== false){
                    $index = str_replace($k, $i, $h);
                    $prev_flag = true;
                    $to_validate[$index] = str_replace('{'.$k.'}', $j, $validations[$i]);
                }
            }
        }
        return Validator::make($data, $to_validate, (!$prev_flag?$rules:$prev_rules));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data){
        /*return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);*/
        return null;
    }

    /*
     * Checks if user already has an existing account.
     *
     * @param array $data
     * @return mixed: 0 - existing flag is N
     *                1 - existing flag is Y
     *                2 - error returned from query
     */
    protected function isExistingSimilarClientUser(array $data){
        $array = array($data['first_name'],$data['surname'],$data['town_or_suburb'],$data['post_code'], $data['email_address'],$data['mobile_phone_number']);
        $this->register->addProcedureOption('NOCOUNT', 'ON');
        $res = $this->register->IsExistingSimilarContactUserID_first($array, ['IsExistingSimilarClientUser'=>'nvarchar(1)']);
        if(isset($res->IsExistingSimilarClientUser)){
            if($res->IsExistingSimilarClientUser === "N")
                return 0;
            else
                return 1;
        }
        return 2;
    }

    /**
     * Checks if the user already has an existing account (with additional information)
     *  
     * @param array $data
     * @return mixed: 0         - error returned from query
     *                user_id   - user id
     */
    protected function findVerySimilarContactUserID($data, $req){
        $birth_date = date_create_from_format('d/m/Y', $data['date_of_birth']);
        $birth_date = date_format($birth_date, 'Y-m-d')." 00:00:00";
        //$array = array($data['first_name'], $data['surname'], $data['email_address'], $data['mobile_phone_number'], $birth_date, $data['unit_number'],$data['street_number'],$data['street_name'],$data['city'],$data['state'],$data['post_code']);
        //$array = array($data['first_name'], $data['surname'], $data['email_address'], $data['mobile_phone_number'], $birth_date, $data['unit_number'],$data['street_number'],$data['street_name'],$data['town_or_suburb'],$data['state'],$data['post_code']);
        $array = array($data['first_name'], $data['surname'], $data['email_address'], $data['mobile_phone_number'], $birth_date, $data['address_line_1'],$data['address_line_2'],$data['town_or_suburb'],$data['state'],$data['post_code']);
        $req->session()->put('user_initial_infos', array_merge($data, $array));
        $this->register->addProcedureOption('NOCOUNT', 'ON');
        $res = $this->register->FindVerySimilarContactUserID_first($array, ['UserID'=>'uniqueidentifier']);
        if(property_exists($res, 'UserID')){
            if($res->UserID === null)
                return 0;
            else
                return 1;
        }
        return 2;
    }

    /**
     * Handle a registration request for the application. This is more of just for confirming the user exists
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerInitialProcess(Request $request){
        $data = $request->all();
        $old_details = $request->session()->get('user_initial_infos');
        $merged = array_merge($old_details, $data);
        // for disclosure checkbox, retain data
        if(!array_key_exists('disclosure', $data) && array_key_exists('disclosure', $merged))
            unset($merged['disclosure']);
        else if(array_key_exists('disclosure', $data))
            $data['disclosure'] = 'checked';
        
        if(!empty($this->validator($data)->errors()))
            $request->session()->put('user_initial_infos', $merged);
        $this->validator($data)->validate();
        $email = $merged['email_address'];
        
        
        // check if user exists
        $res_first_check = $this->isExistingSimilarClientUser($merged);
        /*if($res == 2){ // error, should stay on current page
            Flash::error(trans('messages.confirmation_error'));
            return back();
        }elseif($res == 1){ // user exists, go to loin page.
            Session::flush();
            Flash::error(trans('messages.user_exists'));
            return redirect(route('login'));
        }*/

        // part 2 user existence check
        $res_second_check = $this->findVerySimilarContactUserID($merged, $request);
        /*if($res === 0){ // error, should stay on current page
            Flash::error(trans('messages.user_exists'));
            return back();
        }elseif($res === null){ // user does not exist, proceed with account creation.
            $merged = array_merge($old_details, $data);
            $request->session()->put('user_initial_infos', $merged);
            return redirect(route('register-profile'));
        }else{ // user exists
            Session::flush();
            Flash::error(trans('messages.user_exists'));
            return redirect(route('login'));
        }
        */
        if($res_first_check == 0 || $res_second_check == 0){
            $merged = array_merge($old_details, $data);
            $request->session()->put('user_initial_infos', $merged);
            return redirect(route('register-profile'));
        }elseif($res_first_check == 1 || $res_second_check == 1){
            Session::flush();
            Flash::error(trans('messages.user_exists'));
            return redirect(route('login'));
        }else{
            Flash::error(trans('messages.confirmation_error'));
            return back();
        }

        $request->session()->put('user_initial_infos', $merged);
        return redirect(route('register-profile'));
    }

    public function createProfile(Request $request){
        $data = $request->all();
        $old_details = $request->session()->get('user_initial_infos');
        $merged = array_merge($old_details, $data);
        if(!empty($this->validator($data)->errors()))
            $request->session()->put('user_initial_infos', $merged);
        $this->validator($data)->validate();
        $request->session()->put('user_initial_infos', $merged);
        return redirect(route('register-address'));
    }

    private function CreateContactUser($data, $home_addr_id, $mail_addr_id, $is_home_owner){
        $birth_date = date_create_from_format('d/m/Y', $data['date_of_birth']);
        $birth_date = date_format($birth_date, 'Y-m-d')." 00:00:00";
        //$birth_date = $data['date_of_birth'];
        $array = array($data['oauth_provider'], $data['email_address'], $data['first_name'], $data['middle_name'], $data['surname'], $data['preferred_name'], $home_addr_id, $mail_addr_id, $data['email_address'], $data['mobile_phone_number'], $birth_date, $data['birth_country'], $data['birth_city'], $is_home_owner, $data['organisation_id']);
        return $this->register->CreateContactUser_first($array, ['UserID'=>'uniqueidentifier']);
    }

    private function _CreateAddress($data){
        return $this->register->CreateAddress_first($data, ['AddressID'=>'uniqueidentifier'])->AddressID;
    }

    public function getPrevAddressCount($session){
        $address_count = 0;
        if($session->has('prev_home_address_count'))
            $address_count = $session->get('prev_home_address_count');
        return $address_count;
    }

    public function AddPrevHomeAddressCount(){
        $request = request();
        $session = $request->session();
        $address_count = $this->getPrevAddressCount($session);
        $data = array();
        foreach($session->get('user_initial_infos') as $k=>$i){
            if(strpos($k, "prev") !== false)
                $data['$k'] = $i;
        }
        $_validator = $this->validator($data);
        return $this->previousHomeAddressForm($request, $address_count, $_validator);
    }

    public function displayPrevHomeAddressForm(Request $request){
        if($request->ajax()){
            $session = $request->session();
            $address_count = $this->getPrevAddressCount($session);
            if($address_count == 0){
                return $this->previousHomeAddressAddForm($request);
            }
            $data = array();
            $sess_user_infos = $session->get('user_initial_infos');
            foreach($sess_user_infos as $k=>$i){
                if(strpos($k, "prev") !== false)
                    $data[$k] = $i;
            }
            $data['date_of_birth'] = $sess_user_infos['date_of_birth'];
            $_validator = $this->validator($data);
            return $this->previousHomeAddressForm($request, $address_count, $_validator);
        }else
            Flash::error(trans('messages.not_allowed'));
    }

    public function removePrevHomeAddressCount(){
        $request = request();
        //$to_remove = array('prev_home_unit_number_', 'prev_home_street_number_', 'prev_home_street_name_', 'prev_home_city_', 'prev_home_state_', 'prev_home_post_code_', 'prev_home_country_');
        //$to_remove = array('prev_home_unit_number_', 'prev_home_street_number_', 'prev_home_street_name_', 'prev_home_state_', 'prev_home_post_code_', 'prev_home_country_');
        $to_remove = array('prev_home_address_line_1_','prev_home_address_line_2', 'prev_home_state_', 'prev_home_post_code_', 'prev_home_country_');
        $address_count = $request->session()->get('prev_home_address_count');
        foreach(range(1, $address_count) as $i){
            foreach($to_remove as $j){
                $request->session()->forget($j.$i);
            }
        }
        $request->session()->put('prev_home_address_count', 0);
        return '';
    }

    public function removeSpecificPrevHomeAddress(){
        $request = request();
        //$to_remove = array('prev_home_unit_number_', 'prev_home_street_number_', 'prev_home_street_name_', 'prev_home_state_', 'prev_home_post_code_', 'prev_home_country_');
        $to_remove = array('prev_home_unit_number_', 'prev_home_address_line_2', 'prev_home_state_', 'prev_home_post_code_', 'prev_home_country_');
        $address_count = $request->session()->get('prev_home_address_count');
        if($address_count > 0)
            $address_count--;
        $request->session()->put('prev_home_address_count', $address_count);
        return '';
    }

    private function AddPrevHomeAddressToContactUser($data, $fromdate, $todate){
        $fromdate = date_create_from_format('d/m/Y', $fromdate);
        $fromdate = date_format($fromdate, 'Y-m-d')." 00:00:00";
        $todate = date_create_from_format('d/m/Y', $todate);
        $todate = date_format($todate, 'Y-m-d')." 00:00:00";
        $data[] = $fromdate;
        $data[] = $todate;
        $res = $this->register->AddPrevHomeAddressToContactUser_first($data);
        if($res == 0)
            return true;
        return false;
    }

    public function checkLessThanFiveYearsAndOverlaps($data, $max_years){
        $timestamps = array();
        $years = 0;
        $less_than_five_years_flag = intval($data['length_of_time']) == 0;
        if($less_than_five_years_flag){
            $to_search = "prev_home_from_date";
            $for_replace = "prev_home_to_date";
            foreach($data as $k=>$i){
                if(strpos($k, $to_search) !== false){
                    $_start_date = str_replace("/","-",$data[$k]);
                    $d1 = date_create(date('d-m-Y', strtotime($_start_date)));
                    $index = str_replace($to_search, $for_replace, $k);
                    $_end_date = str_replace("/","-",$data[$index]);
                    $d2 = date_create(date('d-m-Y', strtotime($_end_date)));
                    $timestamps[] = array(strtotime($_start_date), strtotime($_end_date));
                    $years += date_diff($d1, $d2)->y;
                }
            }
            $overlap_flag = false;
            foreach($timestamps as $k=>$i){
                foreach($timestamps as $h=>$j){
                    if($h != $k){
                        if( ($i[0] >= $j[0] && $i[0] <= $j[1]) || ($i[1] >= $j[0] && $i[1] <= $j[1]) ){
                            $overlap_flag = true;
                            break;
                        }
                    }
                }
                if($overlap_flag)
                    break;
            }
            if($years < $max_years)
                Flash::error(trans('messages.insufficient_years_duration'));
            if($overlap_flag)
                Flash::error(trans('messages.prev_addr_dates_overlap'));
        }
        return $less_than_five_years_flag && ($years < $max_years || $overlap_flag);
    }

    /**
     * Create an address
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createFullWithAddresses(Request $request){
        $max_years = 5;
        $data = $request->all();
        $session = $request->session();
        if(isset($data['prev_address_count']))
            $session->put('prev_home_address_indexes', explode(",",$data['prev_address_indexes']));
        $full_data = $session->get('user_initial_infos');

        // for mail home owner checkbox, retain data after validation fail
        if(!array_key_exists('mail_home_owner', $data) && array_key_exists('mail_home_owner', $full_data))
            unset($full_data['mail_home_owner']);
        else if(array_key_exists('mail_home_owner', $data))
            $data['mail_home_owner'] = 'checked';

        // for home owner checkbox, retain data after validation fail
        if(!array_key_exists('home_owner', $data) && array_key_exists('home_owner', $full_data))
            unset($full_data['home_owner']);
        else if(array_key_exists('home_owner', $data))
            $data['home_owner'] = 'checked';
        $merged = array_merge($full_data, $data);

        if(intval($data['length_of_time']) == 1){
            foreach($merged as $k=>$i){
                if(strpos($k, 'prev_home') !== false)
                    unset($merged[$k]);
            }
        }

        $session->put('user_initial_infos', $merged);
        $data['date_of_birth'] = $merged['date_of_birth'];

        if(!empty($this->validator($data)->errors()))
            $request->session()->put('user_initial_infos', $merged);
        $this->validator($data)->validate();

        //check if total duration of dates in years is more than or equal to 5 as required.
        if($this->checkLessThanFiveYears($data, $max_years))
            return back();

        // for home
        $data_address = [
            //$data['unit_number'],
            //$data['street_number'],
            //$data['street_name'],
            $data['address_line_1'],
            $data['address_line_2'],
            $data['town_or_suburb'],
            $data['state'],
            $data['post_code'],
            'Australia',//$data['birth_country'],
            null
        ];
        $res_address_home = $this->_CreateAddress($data_address);
        $data_address = [
            $data['mail_address_line_1'],
            $data['mail_address_line_2'],
            $data['mail_town_or_suburb'],
            $data['mail_state'],
            $data['mail_post_code'],
            $data['mail_country'],
            null
        ];
        $res_address_mail = $this->_CreateAddress($data_address);
        $res_address_prev_home = array();
        if(intval($data['length_of_time']) == 0){
            $address_count = 0;
            foreach($data as $k=>$i){
                if(strpos($k, 'prev_home_address_line_1_') !== false)
                    $address_count++;
            }
            foreach(range(1, $address_count) as $i){
                $data_address = [
                    $data['prev_home_address_line_1_'.$i],
                    $data['prev_home_address_line_2_'.$i],
                    $data['prev_home_town_or_suburb_'.$i],
                    $data['prev_home_state_'.$i],
                    $data['prev_home_post_code_'.$i],
                    $data['prev_home_country_'.$i],
                    null
                ];
                $res_address_prev_home[$i] = $this->_CreateAddress($data_address);
            }
        }
        if($res_address_home === 0 && $res_address_mail === 0){
            Flash::error(trans('messages.address_creation_failed1'));
            return back();
        }else{
            $is_home_owner = 'N';
            if(array_key_exists('home_owner', $data))
                $is_home_owner = 'Y';
            $data = array_merge($full_data, $data);
            $data['organisation_id'] = OrganisationHelper::getCurrentOrganisationID();//$this->policy->getDefaultOrganisation();

            $res_userid = $this->CreateContactUser($data, $res_address_home, $res_address_mail, $is_home_owner);
            /*if($res_userid === 0){
                Flash::error(trans('messages.account_creation_failed'));
                return back();
            }
            elseif($res_userid === 3){*/
            if(!$res_userid || (is_object($res_userid) && (!property_exists($res_userid, 'UserID') || (property_exists($res_userid, 'UserID') && empty($res_userid->UserID))))){
                Session::flush();
                Flash::error(trans('messages.user_exists'));
                return redirect(route('login'));
            }
            if(intval($data['length_of_time']) == 0){
                $prev_addr_flag = true;
                foreach($res_address_prev_home as $k=>$i){
                    $user_address_params = array($res_userid, $i);
                    $prev_addr_flag = $prev_addr_flag && $this->AddPrevHomeAddressToContactUser($user_address_params, $data['prev_home_from_date_'.$k], $data['prev_home_to_date_'.$k]);
                }
                if(!$prev_addr_flag){
                    Flash::error(trans('messages.address_creation_failed2'));
                    return back();
                }
            }
            $this->sendConfirmationEmail($full_data['email_address'], $full_data['first_name'], route('login'));
            return redirect(route('register-email-confirm'));
        }
    }

    public function previousHomeAddressAddForm(Request $request){
        $data = $request->all();
        //$index = $data['index'];
        $index = $request->session()->get('prev_home_address_count');
        if(empty($index) || $index == 0)
            $index = 1;
        else
            $index++;
        if($request->ajax()){
            $request->session()->set('prev_home_address_count', $index);
            return view('AjaxRegAddressForm.ajax_form_plain', [
                        'index'=>$index,'state_options'=>$this->getStates()
                    ])->render();
        }else
            Flash::error(trans('messages.not_allowed'));
    }

    public function previousHomeAddressForm(Request $request, $prev_address_count, $validator){
        $form_errors = null;
        if($validator){
            $form_errors = $validator->errors()->getMessages();
            foreach($form_errors as $k=>$i){
                $form_errors[$k] = reset($i);
            }
        }
        if($prev_address_count > 0){
            $all_details = $request->session()->get('user_initial_infos');
            $output = "";
            foreach(range(1, $prev_address_count) as $i){
                $form_errors_new = array();
                foreach($form_errors as $h=>$k){
                    $search = str_replace('_', ' ', $h);
                    $replace = strpos($search, 'date')?'date':trim(str_replace($i, '', str_replace('_', ' ', $h)));
                    $form_errors_new[$h] = str_replace($search, $replace, $k);
                }
                $output .= view('AjaxRegAddressForm.ajax_form_with_errors', [
                                'i'=>$i,
                                'form_errors'=>$form_errors_new,
                                'all_details'=>$all_details,
                                'prev_address_count'=>$prev_address_count,
                                'state_options'=>$this->getStates()
                            ])->render();
            }
            return $output;
        }
        return '';
    }

    public function sendConfirmationEmail($email, $name, $link){
        Mail::to($email)->send(new RegistrationNotification($name, $link));
    }

    // TODO!!! No stored procedures/instructions for temporary passwords yet.
    /**
     * Send an e-mail containing the temporary password to the user's e-mail address.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendTemporaryPasswordEmail($email){
        return true;
    }

    protected function admin_validator(array $data){
        $validations = array (
            'first_name' => 'required|max:50',
            'surname' => 'required|max:50',
            'username' => 'required|max:50',
            'password' => 'required|max:50|confirmed',
            'email_address' => 'required|email|max:250',
            'organisation_id' => 'required',
            'organisation_role_name' => 'required|max:50',
            'adviser_license_number' => 'regex:/[0-9]/|max:28',
        );
        return Validator::make($data, $validations);
    }
    
    public function createAdminForm(){
        return view('auth.Register.Admin.register');
    }

    public function setPassword($data){
        $user_pass = [$data['username'], bcrypt($data['password'])];
        return $this->register->User_SetPasswordByUsername_first($user_pass);
    }

    public function createAdmin(Request $request){
        $data = $request->all();
        $res = $this->admin_validator($data)->validate();
        $array = [$data['first_name'], $data['surname'], $data['username'], $data['email_address'], $data['organisation_id'], $data['organisation_role_name'], $data['adviser_license_number'], Auth::id()];
        //if(empty($array['adviser_license_number']))
        $res = $this->register->User_CreatePasswordAuthenticated_first($array);
        if(property_exists($res, 'return_value') && $res->return_value === "0"){
            $res = $this->setPassword($data);
            if(property_exists($res, 'return_value') && $res->return_value === "0")
                Flash::success(trans('messages.admin_register_success'));
            elseif(property_exists($res, 'ErrorMessage'))
                Flash::error($res->ErrorMessage);
        }elseif(property_exists($res, 'ErrorMessage'))
            Flash::error($res->ErrorMessage);
        return back();
    }
}