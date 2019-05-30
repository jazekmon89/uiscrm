<?php

namespace App\Http\Requests\Quotes;

use App\Helpers\FormGroupMapHelper;
use App\Helpers\PolicyHelper;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Events\QuoteRequestLodged;
use App\Providers\Facades\Entity;
use Illuminate\Support\Facades\Log;

class RFQFormDataHandler {

	protected $base_key = 'RFQAPI';

	protected $session_key = '';

	protected $requester = null;

	protected $data = [];

	protected $RFQID = null;

    protected $mapper = null;

    protected $Business = null;

    protected $RFQ = null;

    protected $old = null;

    protected $user_contact_id = null;

    public $notify = true;

	public function __construct(RFQRequest $request, PolicyHelper $helper) {
		$this->request = $request;
		$this->helper = $helper;
	}

    public function request()
    {
        return $this->request;
    }

    public function setReferenceRFQ($RFQ)
    {
        $this->old = $RFQ;
    }

    public function setUserContact($user_contact_id)
    {
        $this->user_contact_id = $user_contact_id;
    }

    public function setSessionKey($key) {
        $this->session_key = $key;
    }

	public function getSessionKey() {
        if ($this->session_key) 
            return $this->session_key;
		return $this->session_key = implode(".", [$this->base_key,$this->request->PolicyTypeID,$this->request->FormTypeID]);
	}

	public function setData($data) {
		$this->data = $data;
        return $this;
	}

	public function data() {
		return $this->data = $this->data ?: session($this->getSessionKey());
	}

	public function save() {
		$this->saveRFQ();

		if (!$this->RFQID) 
			return false;

		$this->saveGroupsData();

        if ($this->request->hasBusinessFields() && $this->Business)
            $this->saveInsurableBusiness($this->Business);

		$this->saveCovers();
		$this->saveClaims();

		if ($this->lodge()) 
        {
            $this->RFQ = $this->RFQ ?: Entity::get('RFQ', $this->RFQID, 2);
            
            if ($this->notify)
            {
                event(new QuoteRequestLodged($this->RFQ));
            }

            // return 
            return $this->RFQ;
        }

		return false;
	}

	public function RFQID() {
		return $this->RFQID;
	}

	public function setRequester(\StdClass $requester) {
		$this->requester = $requester;
	}

	protected function lodge() {
        $data = [
            /* RFQ ID          =>*/ $this->RFQID,
            /* NewVersion      =>*/ $this->old ? 'Y' : 'N',
            /* RFQRefNum       =>*/ $this->old ? array_get((array)$this->old, 'RFQRefNum') : NULL,
            /* CurrentUserID   =>*/ $this->requester->user_id
        ];
		return $this->helper->RFQ_Lodge($data);
	}

    protected function getMapHelper() {
        return $this->mapper = $this->mapper ?: new FormGroupMapHelper($this->request);
    }

    public function getRequester() {
        if ($this->requester)
            return $this->requester;
        
        $requester = [
            'name'          => null,
            'email'         => null,
            'phone'         => null,
            'bdate'         => null,
            'user_id'       => null,
            'contact_id'    => null,
            'client_id'     => null,
            'home_addr'     => null,
            'mail_addr'     => null
        ];

        if (Auth::check()) {
            $user = Auth::user();   

            $requester = array_merge($requester, [
                'user_id'       => $user->id,
                'contact_id'    => $user->is_client ? $user->contact_id : null,
                'client_id'     => $user->is_client ? $user->id : null,
                'home_addr'     => $user->is_client ? $user->home_address_id : null,
                'mail_addr'     => $user->is_client ? $user->postal_address_id : null
            ]);
        } 

        return $this->requester = (object)$requester;
    }


    protected function setRequesterFromContactData($data) {
        $this->requester->name  = array_get($data, "RFQ.RequesterName");
        $this->requester->email = array_get($data, "RFQ.EmailAddress");
        $this->requester->phone = array_get($data, "RFQ.PhoneNumber");
        $this->requester->bdate = array_get($data, "RFQ.BirthDate");
        
        #$this->setAddressesFromContactData($data);

        return $this->requester;
    }

    protected function setRequesterContactID($ContactUserID) {
        if ($contact = app(\App\User::class)->Contact_GetByUserID_first($ContactUserID)) {
            $this->requester->contact_id = $contact->ContactID;
            $this->requester->client_id = $ContactUserID;
            $this->requester->home_addr = $contact->HomeAddressID;
            $this->requester->mail_addr = $contact->PostalAddressID;
        }
    }

    protected function setAddressesFromContactData($data) {
        foreach(['mail_addr', 'home_addr'] as $addr) {
            
            if ($address = (array)array_get($data, $addr))
                $this->requester->{$addr} = $this->saveAddress($address);
        }
    }


    protected function overrideAddressesFromContactData($data) {
         foreach(['mail_addr', 'home_addr'] as $addr) {
            if ($address = array_get($data, $addr))
            {
                $this->requester->$addr = $this->saveAddress($address);
            }
        }
    }

	protected function saveRFQ() {
		$ContactDetails = arr_lfind($this->request->groups(false), 'Name', 'ContactDetails');
		$ContactData 	= array_get($this->data(), $ContactDetails->Name);
        $client_id      = $this->user_contact_id ?: array_get($ContactData, "RFQ.ClientUserID");

        $this->getRequester();
        $this->setRequesterFromContactData($ContactData);
        $this->overrideAddressesFromContactData($ContactData);

        $requester =& $this->requester;    
        if ($client_id && !$requester->client_id) 
            $this->setRequesterContactID($client_id);    
    
        $requester->insured_name = array_get($ContactData, "RFQ.InsuredName", "");
        // insured name or company name
        // $requester->company = array_get($ContactData, "Business.CompanyName");
  
        $rfq = [
            /*"OrganisationID" =>*/ $this->request->OrganisationID(),
            /*'PolicyTypeID'   =>*/ $this->request->PolicyTypeID,
            /*'FormTypeID'     =>*/ $this->request->FormTypeID,

            /*'RequesterName'  =>*/ $requester->name,
            /*'PhoneNumber'    =>*/ $requester->phone,
            /*'EmailAddress'   =>*/ $requester->email,
            /*'BirhthDate'     =>*/ $requester->bdate,

            // company name or client's name
            /*'InsuredName'    =>*/ $requester->insured_name,
            
            // be smart here we must identify if the contact data has contactID for
            // adivser and broker
            /*'ContactID'      =>*/ $requester->contact_id,

            // do we need to check if address is saved ?
            // or is address nullable ?
            /*'AddressID'      =>*/ $requester->mail_addr ?: $requester->home_addr,

            // put the current logged in user's ID otherwise null
            /*'CurrentUserID'  =>*/ $requester->user_id
        ];
            // since v1.7.9
            // 09-03-2017 as per change, removed 2 params below
            /* RFQRefNum       =>*/ //$this->old ? array_get((array)$this->old, 'RFQRefNum') : NULL,
            /* NewVersion      =>*/ //$this->old ? 'Y' : 'N' 
        //];

        if (!$RFQ = $this->helper->CreateRFQ_first($rfq, ['RFQID' => 'uniqueidentifier'])) 
        {
        	// halt! we can't proceed anymore since RFQID required for all steps after this
        	return false;
        }
        
        $this->RFQID = $RFQ->RFQID;
        $this->Business = array_get($ContactData, 'Business');
	}
	/**
	* @return void
	*/
	protected function saveInsurableBusiness($data, $addr=null) {
        $addr = $addr ?: $this->requester->mail_addr;

        if (!$addr/* || array_has($data, 'InsurableBusinessID')*/) return;

		$Business = [
            /*'RFQID'                     =>*/ $this->RFQID,
            /*'TradingName'               =>*/ array_get($data, "TradingName"),
            /*'AustralianBusinessNumber'  =>*/ array_get($data, "AustralianBusinessNumber"),
            /*'IsRegisteredForGST'        =>*/ array_get($data, "IsRegisteredForGST"),
            /*'BusinessStructureTypeID'   =>*/ array_get($data, "BusinessStructureTypeID"),
            /*'PostalAddressID'           =>*/ $this->requester->mail_addr ?: $this->requester->home_addr,
            /*'CurrentUserID'             =>*/ $this->requester->user_id,
        ];
        
        $this->helper->RFQ_AddNewInsurableBusiness($Business);
	}

	/**
	* @return AddressID or NULL
	*/
	protected function saveAddress($data, $uid=null, $check=true) {

        if ($check && $AddressID = array_get($data, 'AddressID'))
            return $AddressID;
		$addr = [
            /*'UnitNumber'    =>*/ //array_get($data, 'UnitNumber', ''),
            /*'StreetNumber'  =>*/ //array_get($data, 'StreetNumber', ''),
            /*'StreetName'    =>*/ //array_get($data, 'StreetName', ''),
            /*'AddressLine1'  =>*/ array_get($data, 'AddressLine1', ''),
            /*'AddressLine2'  =>*/ array_get($data, 'AddressLine2', ''),
            /*'City'          =>*/ array_get($data, 'City', ''),
            /*'State'         =>*/ array_get($data, 'State', ''),
            /*'Postcode'      =>*/ array_get($data, 'Postcode', ''),
            /*'Country'       =>*/ "Australia",
            /*'UserID'        =>*/ $uid ?: $this->requester->client_id
        ];
        
        $Address = $this->helper->CreateAddress_first($addr, ['AddressID' => 'uniqueidentifier']);
        
		return $Address ? $Address->AddressID : null;
	}

	protected function saveGroupsData($groups=[], $data=[], $dataPath='') {
		$groups = $groups ?: $this->request->groups(false);
		$data   = $data ?: $this->data();

		foreach($groups as $group) {
			if ($group->Name === 'ContactDetails' || empty($group->questions) && empty($group->children))
                continue;

            $dataPath .= "{$group->Name}.";
            $base_data = (array)array_get($data, $group->Name);

            if ($group->IsRepeating === 'Y')
            	$this->saveRepeatingGroup($group, $base_data, $dataPath);
            else
            	$this->saveGroupQuestions($group, $base_data, $dataPath);

            if ($group->children)
            	$this->saveGroupsData($group->children, $base_data, $dataPath);
		} 
	}

	protected function saveRepeatingGroup($group, $data, $dataPath) {
		foreach($data as $index => $_data) 
            $this->saveGroupQuestions($group, $_data, $dataPath, $index);
	}

	protected function saveGroupQuestions($group, $data, $dataPath, $row=0) {
        if (false === $this->getMapHelper()->checkGroupLinkToQuestions($dataPath, $group->Name)) {
            return;
        }

		foreach($group->questions as $question) {
            // if the question is linked from other questions then if the questions 
            // linked answers matches we validate other wise ignore
            if (false === $this->getMapHelper()->checkQuestionLinkToSiblings($dataPath, $question->FormQuestionID))
                continue; 

            if ($question->FormQuestionTypeName === 'Address') {
                if (array_get($data, "{$question->FormQuestionID}.use_address")) {
                    $answer = $this->requester->mail_addr ?: $this->requester->home_addr;
                }
            	else $answer = $this->saveAddress((array)array_get($data, $question->FormQuestionID), null, false);
            } else {
                $answer = array_get($data, $question->FormQuestionID);
            }
            foreach((array) $answer as $ans) {
                
                if ($ans) {
                    if ($question->FormQuestionTypeName === 'Date' || $question->FormQuestionTypeName === 'Datetime')
                    {
                        $ans = Carbon::createFromFormat("d/m/Y", $ans)->format("Y-m-d H:i:s");
                    }

                    $qdata = [
                        /*'RFQID'                         =>*/ $this->RFQID,
                        /*'FormQuestionGroupID'           =>*/ $group->FormQuestionGroupID,

                        // @see mssql_guid for higher php version
                        /*'FormQuestionGroupInstanceID'   =>*/ row_guid($group->FormQuestionGroupID, $row),

                        /*'FormQuestionID'                =>*/ $question->FormQuestionID,
                        /*'Answer'                        =>*/ $ans,
                        /*'CurrentUserID'                 =>*/ $this->requester->user_id
                    ];
                    #error_log(print_r($qdata + ['QNAME' => $question->Name], 1));
                    $this->helper->RFQ_AddNewFormQuestionAnswer($qdata);
                }

            }
        }
	}

	protected function saveCovers() {
		if (!($Covers = $this->request->covers()) || ($data = array_get($this->data(), 'Covers')))
			return;

		// make sure also policy has CoverSets
		
		if (!empty($data['Set']) && !empty($Covers['Sets']) 

			// the set that is selected must exists in the Covers
			&& $Set = arr_lfind($Covers['Sets'], "CoverLevelSetID", $data['Set'])) {

            if ($Set->levels) {
                foreach($Set->levels as $level) {
                    $items[] = [
                        /*'RFQID'         =>*/ $this->RFQID,
                        /*'CoverID'       =>*/ $level->CoverID,
                        /*'CoverLevelID'  =>*/ $level->CoverLevelID,
                        /*'CurrentUserID' =>*/ $this->requester->user_id
                    ];
                }
            } else {
                $items[] = [
                    /*'RFQID'         =>*/ $this->RFQID,
                    /*'CoverID'       =>*/ $Set->CoverLevelSetID,
                    /*'CoverLevelID'  =>*/ null,
                    /*'CurrentUserID' =>*/ $this->requester->user_id
                ];
            }
        }

        // make sure also there's covers and data
        if (($data = array_get($data, 'Covers.Covers')) && ($covers = array_get($Covers,'Cover'))

        	// always check trigger
        	&& 'Y' !== array_get($data, 'Cover.optional-trigger')) {
        	

	        foreach($covers as $cover) {
	        	$save = array_get($data, $cover->CoverID);
	        	if ($cover->levels) {
	                foreach($cover->levels as $level) {
	                    $items[] = [
	                        /*'RFQID'         =>*/ $this->RFQID,
	                        /*'CoverID'       =>*/ $cover->CoverID,
	                        /*'CoverLevelID'  =>*/ $level->CoverLevelID,
	                        /*'CurrentUserID' =>*/ $this->requester->user_id
	                    ];
	                }
	            } else {
	                $items[] = [
	                    /*'RFQID'         =>*/ $this->RFQID,
	                    /*'CoverID'       =>*/ $cover->CoverID,
	                    /*'CoverLevelID'  =>*/ null,
	                    /*'CurrentUserID' =>*/ $this->requeter->user_id
	                ];
	            }
	        }
	    }

	    foreach($items as $item) $this->helper->RFQ_AddRequestedCover($item);
	}

	protected function saveClaims() {
        $data = array_get($this->data(), 'Claims');
        $trigger1 = array_get($data, 'trigger.claims') === 'Y';
        $trigger2 = array_get($data, 'trigger.others') === 'Y';
        
        if ($trigger1 && $Claims = array_get((array)$data, "Claims")) 
        {
            $this->_saveClaims($Claims);   
        }
        if ($trigger2 && $Others = array_get((array)$data, 'OtherClaims'))
        {
            $this->_saveClaims($Others);
        }
		
	}

    protected function _saveClaims($Claims)
    {
        foreach($Claims as $claim) {

            //$begin = array_get($claim, 'InsurancePeriodBeginDate');
            //$end = array_get($claim, 'InsurancePeriodEndDate');
            $accident_date = array_get($claim, 'InsuranceAccidentDate');
            //$save = [
                /*'RFQID'                     =>*/ //$this->RFQID,
                /*'InsurerCompanyName'        =>*/ //array_get($claim, 'InsurerCompanyName'), 
                /*'InsurancePeriodBeginDate'  =>*/ //$begin ? Carbon::createFromFormat("m/Y", $begin)->format("mY") : null,
                /*'InsurancePeriodEndDate'    =>*/ //$end ? Carbon::createFromFormat("m/Y", $end)->format("mY") : null,
                /*'TypeOfClaim'               =>*/ //array_get($claim, 'TypeOfClaim'), 
                /*'AmountPaid'                =>*/ //array_get($claim, 'AmountPaid'),
                /*'AtFault'                   =>*/ //array_get($claim, 'AtFault', 'N'),
                /*'Finalized'                 =>*/ //array_get($claim, 'Finalized', 'N'),
                /*'CurrentUserID'             =>*/ //$this->requester->user_id
            //];
            $save = [
                /*'RFQID'                     =>*/ $this->RFQID,
                /*'InsurerCompanyName'        =>*/ array_get($claim, 'InsurerCompanyName'),
                /*'TypeOfClaim'               =>*/ array_get($claim, 'TypeOfClaim'), 
                /*'AmountPaid'                =>*/ array_get($claim, 'AmountPaid'),
                /*'AtFault'                   =>*/ array_get($claim, 'AtFault', 'N'),
                /*'Finalized'                 =>*/ array_get($claim, 'Finalized', 'N'),
                /*'InsuranceAccidentDate'     =>*/ $accident_date ? Carbon::createFromFormat("m/Y", $accident_date)->format("Y-m-d H:i:s") : null,
                /*'Finalized'                 =>*/ array_get($claim, 'Description',''),
                /*'CurrentUserID'             =>*/ $this->requester->user_id
            ];

            $this->helper->RFQ_AddNewPreviousClaim($save, ['PreviousClaimID' => 'uniqueidentifier']);
        }
    }
}