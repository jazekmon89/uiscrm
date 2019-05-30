<?php

namespace App\Http\Requests\Quotes;

use App\Helpers\FormGroupMapHelper;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class RFQRequestValidator
{
    protected $form = null;

    protected $mapper = null;

    protected $errors = [];

    protected $data = [];

    protected $request = null;

    public $attributes = [];

    public $messages = [];

    public function __construct(FormRequest $request, $form) {
        $this->form = $form;
        $this->request = $request;

        $this->data = $request->all();
    }

    public function errors() {
        return $this->errors;
    }

    public function data($key=null) {
        return $key ? array_get($this->data, $key) : $this->data;
    }

    protected function covers() {
        return (array)array_get($this->form, 'Covers');
    }

    public function validateAddress($data, $error_index) {
        $user = Auth::check() ? Auth::user() : null;

        /**
        * @todo validate AddressID
        */
        if ($AddressID = array_get($data, 'AddressID')) {
            
            if (!$this->request->helper()->Address_Get_first($AddressID)) {
                array_set($this->errors, $error_index, ["Address is not found."]);
            }
            
            return;
        }

        $factory = $this->getValidatorInstance();

        $field_rules = [
            //'UnitNumber' => 'nullable',
            //'StreetNumber' => 'required',
            //'StreetName' => 'required',
            'AddressLine1' => 'required',
            'AddressLine2' => 'nullable',
            'City' => 'required',
            'State' => 'required|in:'. implode(',', array_keys(all_states())),
            'Postcode' => 'required',
            // 'Country' => 'required'
        ];

        $validator = $factory->make((array)$data, $field_rules, $this->messages(), $this->attributes());
        
        if ($validator->fails()) 
            array_set($this->errors, $error_index, $validator->getMessageBag()->toArray());
    }

    protected function validateContactDetails($data) {
        $factory = $this->getValidatorInstance();

        $validations = [
            'RFQ' => [
                'RequesterName'             => 'required',
                'PhoneNumber'               => 'required',
                'EmailAddress'              => 'required|email',
                'BirthDate'                 => 'nullable|date_format:d/m/Y',
                'InsuredName'               => 'required',
            ],
            'Business' => [
                // 'CompanyName'               => 'required',
                'TradingName'               => 'nullable',
                //'AustralianBusinessNumber'  => 'numeric|digits:11',
                'AustralianBusinessNumber'  => 'nullable',
                'IsRegisteredForGST'        => 'nullable|in:Y,N',
                'BusinessStructureTypeID'   => 'required'
            ]
        ];

        $user = Auth::check() ? Auth::user() : null;
        if ($user) {
            /**
             * @see FRS v1.6 InsuredName and CompanyName are different fields
             */
            $validations['RFQ'] = ['InsuredName' => 'required'];

            // if ($user->is_adviser) {
            //     $validations['RFQ']['ClientUserID'] = 'required';
            // }
        }

        if (!$this->request->hasBusinessFields() /*|| array_get($data, 'InsurableBusinessID')*/) {
            /**
             * @todo validate InsurableBusinessID 
             * @see FRS v1.6 JCI only fields (birthdate, home address, business)
             */
            unset($validations['Business']);
        }
        foreach($validations as $group => $fieldRules) {   
            $this->validator = $factory->make((array)array_get($data, $group), $fieldRules);
        
            if ($this->validator->fails())  {
                array_set($this->errors, "ContactDetails.$group", $this->validator->getMessageBag()->toArray());
            }   
        }

        /**
         * @see FRS v1.6 JCI only fields (business & mail address)
         */
        if ($mail_addr = (array)array_get($data, 'mail_addr')) 
            $this->validateAddress($mail_addr, "ContactDetails.mail_addr");

        /**
         * @see FRS v1.6 UIS only fields (birthdate, home address, business & mail address)
         */
        if ($home_address = (array)array_get($data, 'home_addr'))
            $this->validateAddress($home_address, "ContactDetails.home_addr");
    }

    protected function validateDynamicGroups($group, $data=[], $error_index="") {

        if (false === $this->getMapHelper()->checkGroupLinkToQuestions($error_index, $group->Name)) {
            return;
        }

        if ($group->IsRepeating === 'Y') {
            // separate repeatable groups since the index is numeric
            // also so we can have a recursive method in a recursive method
            $this->validateRepeatableGroups($group, (array)$data, $error_index . $group->Name);
        } else {
            $this->validateDynamicQuestions($group, $data, $error_index . $group->Name);
        }
        if ($group->children) {
            foreach($group->children as $child) {
                // make sure we're on the level of the depth of form data we 
                // expected so that we validate fields properly
                $child_data = (array)array_get($data, $child->Name);
                $this->validateDynamicGroups($child, $child_data, $error_index . "{$group->Name}.");
            }
        }
        if (isset($group->partials) && $group->partials) {
            foreach($group->partials as $partial => $tmpl) {
                if (method_exists($this, 'validatePartial' . $partial)) {

                    // partial data is inline with top level groups
                    // hince we save them separately
                    call_user_func_array([$this, 'validatePartial' . $partial], [(array)$this->data($partial)]);
                }
            }
        }
    }

    protected function getMapHelper() {
        return $this->mapper = $this->mapper ?: new FormGroupMapHelper($this->request);
    }

    protected function validateRepeatableGroups($group, $data, $error_index) {
        /**
        * For repeatable groups we need to assume data always has 
        * if not we put value as is so that we'll have some thing to validate
        */
        $data = $data ?: array($data);
        foreach((array)$data as $index => $_data) {
            $this->validateDynamicQuestions($group, $_data, $error_index . ".$index");
        }
    }

    protected function validateDynamicQuestions($group, $data, $error_index) {
        $factory = $this->getValidatorInstance();
        $rules   = [];
        foreach($group->questions as $question) {
            // if the question is linked from other questions then if the questions 
            // linked answers matches we validate other wise ignore
            if (false === $linked = $this->getMapHelper()->checkQuestionLinkToSiblings($error_index, $question->FormQuestionID))
                continue; 

            $field_rules = [];
            if ($question->IsMandatory === 'Y' || $linked) 
                $field_rules[] = "required";

            if ($question->FormQuestionTypeName === 'Address') {
                // put this outside switch since we can't skip loop 
                if (!array_get($data, $question->FormQuestionID .'.use_address'))
                    $this->validateAddress(array_get($data, $question->FormQuestionID), $error_index .'.'. $question->FormQuestionID); 
                continue;
            }
            switch($question->FormQuestionTypeName) {
                
                // ? is there is options for boolean 
                case 'Boolean': $field_rules[] = 'in:Y,N'; break;

                // ? be smart here to detect birthdate
                case 'Date': 
                case 'DateTime':
                    if (preg_match('#(birthdate|birth)#i', $question->Name))
                        $field_rules[] = 'date_format:d/m/Y';
                    else $field_rules[] = 'date_format:d/m/Y H:i';
                break;
                // ? be smart here do we need to check digit limit for number ?
                case 'Number': $field_rules[] = 'numeric'; break;
                case 'SelectOne': $field_rules[] = 'in:'. implode(',', arr_lget($question->choices, 'FormQuestionPossChoiceID')); break;
                #case 'SelectMulti': $field_rules = ['present|min:1']; break;
            }     
            $rules[$question->FormQuestionID] = implode('|', $field_rules);
        } 
        $validator = $factory->make((array)$data, $rules, $this->messages(), $this->attributes());
            if ($validator->fails()) {
                foreach((array)$validator->getMessageBag()->toArray() as $id => $errors)
                    array_set($this->errors, $error_index . ".$id", $errors);
            }
    }

    protected function validatePartialCovers($data) {
        $Covers     = (array)$this->covers(); 
        $sets       = (array)array_get($Covers, 'Sets');
        $SetData    = (array)array_get($data, 'Set');
        $covers     = (array)array_get($Covers, 'Covers');
        $CoverData  = (array)array_get($data, 'Cover');

        if ($sets && !$SetData) 
            array_set($this->errors, "Covers.Set", "Please select out/contents/stocks sum.");

        if ($covers) {
            foreach($covers as $cover) {
                if ($cover->IsMandatory === 'Y' && !array_has($CoverData, $cover->CoverID)) {

                    // if a cover has levels means the levels are the options
                    // otherwise a cover is a checkbox
                    $msg = $cover->levels ? "Please choose ({$cover->displayText})" : "You are required to add this option";

                    // make sure we index the same as the form 
                    array_set($this->errors, "Covers.Cover.{$cover->CoverID}", $msg);
                }

                // check only covers that has levels since they are not mandatory or recommended
                else if ($cover->levels 

                    // empty data means no level selected
                    && ($check = array_get($data, $cover->CoverID)) 

                    // if so we need to check if its one of the levels
                    // use get instead of has since we could also have empty value
                    && !arr_lfind($cover->levels, 'CoverLevelID', $check)) {
                    
                    // make sure we index the same as the form 
                    array_set($this->errors, "Covers.Cover.{$cover->CoverID}", "Please choose from ({$cover->displayText}) options.");
                }
            }
        }
    }

    protected function validatePartialClaims($data) 
    {
        $trigger1 = array_get((array)$data, 'trigger.claims');
        $trigger2 = array_get((array)$data, 'trigger.others');

        if ($trigger1 === 'Y' && $Claims = array_get((array)$data, "Claims")) 
        {
            $this->_validateClaims($Claims);   
        }
        if ($trigger2 === 'Y' && $Others = array_get((array)$data, 'OtherClaims'))
        {
            $this->_validateClaims($Others, "OtherClaims");
        }
    }

    protected function _validateClaims($Claims, $category="Claims") 
    {
        $factory = $this->getValidatorInstance();
        $_first_claim = count($Claims)?$Claims[0]:$Claims;
        $policy_type_text = '';
        if($_first_claim && $category === 'Claims'){
            $_policy_type = $this->request->helper()->PolicyType_Get_first($_first_claim['TypeOfClaim']);
            $policy_type_text = $_policy_type->DisplayText;
        }
        $rules = [
            'InsurerCompanyName' => 'required',
            'InsuranceAccidentDate' => 'required|date_format:m/Y',
            //'InsurancePeriodBeginDate' => 'required|date_format:m/Y',
            //'InsurancePeriodEndDate' => 'required|date_format:m/Y|after:InsurancePeriodBeginDate',
            'TypeOfClaim' => 'required',
            //'AtFault' => 'nullable|in:Y,N',
            //'Finalized' => 'nullable|in:Y,N',
            'AtFault' => 'required|in:Y,N',
            'Finalized' => 'required|in:Y,N',
            'AmountPaid' => 'required|numeric',
            'Description' => 'nullable'
        ];
        if($policy_type_text == 'Workers Compensation')
            $rules['AtFault'] = 'nullable|in:Y,N';

        if ($category === 'Claims')
        {
            $rules['TypeOfClaim'] .= "|in:". implode(',', arr_lget($this->request->helper()->getTypes($this->request->OrganisationID()), "PolicyTypeID"));
        }

        foreach((array)$Claims as $index => $claim) {
            $validator = $factory->make($claim, $rules, $this->messages(), $this->attributes());
            
            if ($validator->fails()) {
                array_set($this->errors, "Claims.{$category}.{$index}", $validator->getMessageBag()->toArray());
            }
        }
        $loop_break = false;
        foreach((array)$Claims as $index1 => $claim1) {
            foreach((array)$Claims as $index2 => $claim2) {
                if($index1 != $index2 && $claim1 == $claim2){
                    $conflicting1 = [
                        'InsurerCompanyName' => ['Cannot repeat the same answer.'],
                        'InsuranceAccidentDate' => ['Cannot repeat the same answer.'],
                        'AmountPaid' => ['Cannot repeat the same answer.'],
                        'AtFault' => ['Cannot repeat the same answer.'],
                        'Finalized' => ['Cannot repeat the same answer.']
                    ];
                    $conflicting2 = [
                        'InsurerCompanyName' => ['Cannot repeat the same answer.'],
                        'InsuranceAccidentDate' => ['Cannot repeat the same answer.'],
                        'AmountPaid' => ['Cannot repeat the same answer.'],
                        'AtFault' => ['Cannot repeat the same answer.'],
                        'Finalized' => ['Cannot repeat the same answer.'],
                    ];
                    if($category !== 'Claims'){
                        $conflicting1['TypeOfClaim'] = ['Cannot repeat the same answer.'];
                        $conflicting2['TypeOfClaim'] = ['Cannot repeat the same answer.'];
                    }
                    array_set($this->errors, "Claims.{$category}.{$index1}", $conflicting1);
                    array_set($this->errors, "Claims.{$category}.{$index2}", $conflicting2);
                    $loop_break = true;
                    break;
                }
            }
            if($loop_break)
                break;
        }
    }

    public function validateGroup($group, $data=[]) {
        if ($data) $this->data($data);
        if ($group->Name === 'ContactDetails') 
            $this->validateContactDetails($this->data($group->Name));
        else 
            $this->validateDynamicGroups($group, $this->data($group->Name));

        return empty($this->errors) ? true : false;
    }

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        return app(ValidationFactory::class);
    }

    public function messages() {
        return $this->messages;
    }

    public function attributes() {
        return $this->attributes;
    }
}
