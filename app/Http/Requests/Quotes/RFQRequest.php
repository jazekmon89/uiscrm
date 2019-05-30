<?php

namespace App\Http\Requests\Quotes;

use App\Helpers\PolicyHelper;
use App\Http\Requests\Quotes\FRQRequestValidator;
use App\Helpers\OrganisationHelper;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class RFQRequest extends FormRequest
{
    protected $form = null;

    protected $group = null;

    protected $errors = [];

    protected $validator = null;

    protected $dataSupplier = null;

    public function __construct(PolicyHelper $helper) {
        $this->helper = $helper;
    }

    public function setHelper(PolicyHelper $helper) {
        $this->helper = $helper;
    }

    public function form() {
        if (!$this->form)
            $this->form = $this->helper->getForm($this->PolicyTypeID, $this->FormTypeID, $this->OrganisationID());
        return $this->form; 
    }

    public function helper() {
        return $this->helper;
    }

    public function forJCI() {
        return $this->OrganisationID() == OrganisationHelper::getOrganisationIDByName("Just Coffee Insurance");
    }

    public function forUIS() {
        return $this->OrganisationID() == OrganisationHelper::getOrganisationIDByName('Ultra Insurance Solutions');
    }

    public function hasBusinessFields() {
        if ($this->forJCI()) return true;

        $UIS_Forms = [
            '7973CAC2-98BA-E611-8144-02A5618F3995', '7A73CAC2-98BA-E611-8144-02A5618F3995'
        ];

        if ($this->forUIS() && in_array($this->PolicyTypeID, $UIS_Forms))
            return true;
        return false;
    }

    public function OrganisationID() {
        return $this->OrganisationID = $this->OrganisationID ?: OrganisationHelper::getDefaultOrganisation();
    }

    public function groups($toponly=true) {
        if (!$this->form()) return [];

        $groups = array_get((array)$this->form(), "Groups");

        foreach($groups as $key => &$group) {
            $this->attachGroupChildrenAndQuestions($group);

            if ($group->Name !== 'ContactDetails' && empty($group->children) && empty($group->questions))
                unset($groups[$key]);
        }
        

        return (array)$groups;
    }


    public function covers() {
        if (!$this->form()) return [];

        return [] /*(array)$this->helper->getPolicyCovers($this->PolicyTypeID)*/;    
    }

    /**
    * @return GUID group id to validated
    */
    public function group() {
        if (!$this->group) 
            $this->group = (object)array_get($this->form(), "Groups.{$this->GroupID}");

        // if no group found eg: for validating a form group 
        // no validation process occur
        return $this->group ? $this->attachGroupChildrenAndQuestions($this->group) : false;
    }

     public function errors() {
        return $this->errors;
    }

    public function data($key=null) {
        $base_data = $this->hasGroup() ? $this->Input($this->group()->Name) : $this->all();

        return $key ? array_get($base_data, $key) : $base_data;
    }

    public function hasGroup() {
        return $this->GroupID ? array_has((array)$this->form(), "Groups.{$this->GroupID}") : false;
    }

    protected function attachGroupChildrenAndQuestions(&$group) {
        if ($group->Name === 'ContactDetails')
            return $group;
        
        // make sure group is ready for validation
        $this->helper->attachGroupChildrenAndQuestions($group);

        /**
         * @see FRS only JCI has Covers & claims
         */
        if (!$this->forJCI())
            return $group;

        $group->partials = [];
        if ($group->Name === 'iInsuranceOptions') {
            $group->partials = ["Covers" => "Quotes.Form.Covers"];        
        }
        elseif($group->Name === 'InsuranceHistory') {
            $group->partials = ["Claims" => "Quotes.Form.Claims"];
        }
        
        return $group;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function validateSessionData($basekey="RFQAPI", $data=[]) {
        $data = session("{$basekey}.{$this->PolicyTypeID}.{$this->FormTypeID}");

        $cnt = 0;
        foreach($this->groups(false) as $group) {

            if (!isset($data[$group->FormQuestionGroupID]))
                array_set($this->errors, "missing_data.$cnt", $group->FormQuestionGroupID);

            if (isset($group->partials) && $group->partials) {
                foreach($group->partials as $partial => $tmpl) {

                    if ($partial !== 'Claims' && !isset($data[$partial])) {
                        array_set($this->errors, "missing_partials.$cnt", $partial);
                    }
                }
            }

            $cnt++;        
        }           
    }

    public function validateApiData($data) {
        $cnt = 0;
        $validator = $this->getRequestValidator();
        foreach($this->groups(false) as $group) 
        {

            $validator->validateGroup($group);
            if ($errors = $validator->errors()) {
               $this->errors = array_merge($this->errors, $errors); 
            }    
        }     
    }

    protected function validateForm() {
        if (!$this->form()) {
            $this->errors = ['Form' => "No form found for Policy #{$this->PolicyTypeID}"];

        }
        if ($this->route()->hasParameter('GroupID') && !$this->hasGroup())
            $this->errors = ['Form' => "No group #{$this->GroupID} for Policy #{$this->PolicyTypeID}"];
    }

    public function validate() {
        if (! $this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        /**
         * @see App\Helpers\PolicyHelper::isPolicyForm
         */
        $this->validateForm();

        if ($this->errors()) 
            return $this->_failedValidation();

        return true;
    }

    protected function getRequestValidator() {
        $form           = (array)$this->form();
        $form['Covers'] = (array)$this->covers();

        // make we let validator know about covers/partials also
        return $this->validator = $this->validator ?: new RFQRequestValidator($this, $form);
    }

    public function validateGroup() {
        $validator = $this->getRequestValidator();
        $group = $this->group();
        
        $validator->validateGroup($this->group());

        if ($errors = $validator->errors()) {
            return $this->_failedValidation($errors);
        }
        return true;
    }

    public function setDataSupplier($supplier)
    {
        if (is_callable($supplier))
        {
            $this->dataSupplier = $supplier;
        }
        return $this;
    }

    public function getDataSupplier()
    {
        return $this->dataSupplier ?: [$this, 'supplyClientData'];
    }

    public function generateHtml($api=true, $supplier=null) 
    {
        $groups = $this->groups(false);
        $Covers = $this->covers();

        $OrganisationID = $this->OrganisationID();
        $PolicyTypeID = $this->PolicyTypeID;
        $FormTypeID = $this->FormTypeID;
        

        if (is_callable($supplier)) 
        {
            $this->setDataSupplier($supplier);
        }
        
        if ($supplier = $this->getDataSupplier())
        {
            if(count($supplier)){
                $data = $supplier[0]->RFQ;
                if(!isset($data['InsurableBusiness']['PostalAddress']['AddressLine1']) 
                && isset($data['InsurableBusiness']['PostalAddress']['Address1']))
                    $data['InsurableBusiness']['PostalAddress']['AddressLine1'] = $data['InsurableBusiness']['PostalAddress']['Address1'];
                if(!isset($data['InsurableBusiness']['PostalAddress']['AddressLine2'])
                && !isset($data['InsurableBusiness']['PostalAddress']['Address2']))
                    $data['InsurableBusiness']['PostalAddress']['AddressLine2'] = $data['InsurableBusiness']['PostalAddress']['Address2'];
                $supplier[0]->RFQ = $data;
            }
            call_user_func_array($supplier, [app('request')]);
        }
        $html = view("Quotes.Api.Form", compact('groups', 'Covers', 'PolicyTypeID', 'FormTypeID', 'OrganisationID', 'api'));

        return $html;
    }

    protected function supplyClientData($request)
    {        
        if (Auth::check() && Auth::user()->is_client) {
            $user = Auth::user();
            /**
            * @todo prefill clients data on contact details, situation at risks etc.
            */
            $data['ContactDetails'] = [
                'RFQ' => [
                    'RequesterName' => $user->name,
                    'EmailAddress'  => $user->email,
                    'PhoneNumber'   => $user->mobile_phone_number,
                    'BirthDate'     => $user->birth_date ? date('m/d/Y', strtotime(Auth::user()->birth_date)) : ''
                ]
            ];

            if ($this->forUIS()) {
                /**
                * @todo @situation at risks section prefill home address
                * @todo for data handling also skip is user is client or lodge by adviser/staff
                */

                $data['ContactDetails']['home_addr'] = (array)$user->home_address;
            }
            if ($this->hasBusinessFields()) {
                
                if ($clients = $user->clients) {
                    if (!$request->ClientID 
                        || !$client = arr_lfind($user->clients, "ClientID", $request->ClientID)) {

                        // double check client belongs to user:contact
                        $client = head($user->clients);
                    }
                    $data['ContactDetails']['RFQ']['InsuredName'] = array_get($client, "InsuredName");

                    if (($businessID = array_get($client, 'InsurableBusinessID')) &&
                        $business = (array)$user->InsurableBusiness_Get_first($businessID)) {

                        $data['ContactDetails']['Business'] = $business /*+ ['InsurableBusinessID' => $businessID]*/;

                        if ($business['PostalAddressID'])
                            $data['ContactDetails']['mail_addr'] = (array)$user->Address_Get_first($business['PostalAddressID']);
                    }
                }
                
                if (!array_get($data, 'ContactDetails.mail_addr'))
                    $data['ContactDetails']['mail_addr'] = (array)Auth::user()->mail_address;
            }            

            if(!isset($data['ContactDetails']['mail_addr']['AddressLine1']) 
            && isset($data['ContactDetails']['mail_addr']['Address1']))
                $data['ContactDetails']['mail_addr']['AddressLine1'] = $data['ContactDetails']['mail_addr']['Address1'];
            if(!isset($data['ContactDetails']['mail_addr']['AddressLine2'])
            && !isset($data['ContactDetails']['mail_addr']['Address2']))
                $data['ContactDetails']['mail_addr']['AddressLine2'] = $data['ContactDetails']['mail_addr']['Address2'];

            $request->merge($data);
        }
    }


    protected function _failedValidation($errors=[])
    {
        throw new ValidationException(null, $this->response(
            $errors ?: $this->errors()
        ));
    }

     /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if ($this->expectsJson() 
            || $this->ajax === 1
            || $this->route() && $this->route()->getPrefix() === 'api') {
            return new JsonResponse($errors, 200);
        }
        return $this->redirector->to($this->getRedirectUrl())
                                        ->withInput($this->except($this->dontFlash))
                                        ->withErrors($errors, $this->errorBag);
    }
}
