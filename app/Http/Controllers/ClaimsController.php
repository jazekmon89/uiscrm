<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Helpers\OrganisationHelper;
use App\Helpers\PolicyHelper;
use App\Helpers\ClientHelper;
use App\Http\Controllers\Controller;
use App\Attachment;
use App\Claims;
use App\Http\Controllers\TasksController;
use DateTime;

use Illuminate\Support\Facades\Auth;
use Flash;

use App\Providers\Facades\Entity;

class ClaimsController extends Controller
{
	protected $claims = null, $policy = null, $client = null;

    public function __construct(ClientHelper $client, PolicyHelper $helper) {
        $this->client = $client;
        $this->policy = $helper;
        $this->claims = new Claims;
        $this->middleware('auth');
        $max_records_to_display = 50;
    }

    private function getPolicyIDs($client_id){
        if(!$policy_ids = $this->policy->Client_GetCurrentPolicyIDs($client_id))
            abort(404);
        return $policy_ids;
    }

    public function index($ClientID = null){
        /*$client_id = $this->getClientID();
        $policy_ids = $this->getPolicyIDs($client_id);
        $insurance_policies = [];
        $policies = [];
        $insurance_policies_minified = [];
        $client = $this->claims->Client_Get_first($client_id);
        $InsuredName = $client->InsuredName;
        foreach($policy_ids as $i){
            $insurance_policy = $this->policy->InsurancePolicy_Get_first($i->InsurancePolicyID);
            $insurance_quote = $this->policy->InsuranceQuote_Get([$insurance_policy->InsuranceQuoteID]);
            $expiry_date = null;
            if(is_object($insurance_quote) && property_exists($insurance_quote, 'ExpiryDateTime'))
                $expiry_date = date("Y-m-d", strtotime($insurance_quote->ExpiryDateTime));
            else
                $expiry_date = '';
            $expiry_date_with_InsuredName = " - ".$expiry_date." {$InsuredName}";
            $policy = $this->policy->PolicyType_Get_first($insurance_policy->PolicyTypeID);//$rfq->PolicyTypeID);
            $policy_ref_num = $insurance_policy->PolicyRefNum;
            $policy_ref_num = (!empty($policy_ref_num)?"(":"").$policy_ref_num.$expiry_date_with_InsuredName.(!empty($policy_ref_num)?")":"");
            $insurance_policies[$i->InsurancePolicyID] = ['DisplayText'=>$policy->DisplayText.'<br />'.$policy_ref_num, 'Description'=>$policy->Description,'PolicyTypeID'=>$policy->PolicyTypeID, 'PolicyRefNum'=>$insurance_policy->PolicyRefNum];
            $insurance_policies_minified[$i->InsurancePolicyID] = ["DisplayText"=>$policy->DisplayText.' '.$policy_ref_num,"PolicyTypeID"=>$policy->PolicyTypeID];
            $policies[$policy->PolicyTypeID] = $policy->DisplayText.' '.$policy_ref_num;
        }
        if(Auth::user()->_is_client)
            $selection_form = $this->getSelectionForm($insurance_policies_minified, $policies)->render();
        else
            $selection_form = $this->getSelectionForm($insurance_policies_minified, $policies, $InsuredName, $CompanyName)->render();
        return view('Claims.claim-request', compact('insurance_policies','selection_form'));*/
        //$policy_types = OrganisationHelper::getPolicyTypes();
        $policies = OrganisationHelper::getPolicyIDs();
        if(!$policies)
            abort(404);
        if(!$ClientID)
            $ClientID = $this->client->getClientID();
        if(!$ClientID)
            return view('Claims.claim-request', ['no_policies'=>true]);
        $no_policies = false;
        $client_infos = $this->claims->Client_Get_first($ClientID);
        $InsuredName = null;
        if($client_infos)
            $InsuredName = $client_infos->InsuredName;
        $insurance_policies = [];
        foreach($policies as $k=>$i){
            $policy = $this->claims->InsurancePolicy_Get_first($i->InsurancePolicyID);
            if(!$policy)
                abort(404);
            $claim_types = $this->claims->PolicyType_GetClaimTypeIDs($policy->PolicyTypeID);
            $policy_type = $this->claims->PolicyType_Get_first($policy->PolicyTypeID);
            if(count($claim_types)){
                $expiry_date = $policy->ExpiryDateTime?date("Y-m-d", strtotime($policy->ExpiryDateTime)):'';
                $display_text = $policy_type->DisplayText.' '.($policy->PolicyRefNum?('('.$policy->PolicyRefNum.(!empty($expiry_date)?' - '.$expiry_date:'').(!empty($InsuredName)?' - '.$InsuredName:'').')'):'');
                $insurance_policies[$i->InsurancePolicyID] = (object)['PolicyTypeID'=>$policy_type->PolicyTypeID, 'DisplayText'=>$display_text];
            }
        }
        $Org_id = OrganisationHelper::getCurrentOrganisationID();
        $selection_form = Auth::user()->_is_client?$this->getSelectionForm($insurance_policies,$Org_id)->render():$this->getSelectionForm($insurance_policies,$Org_id, $InsuredName, $CompanyName = null)->render();
        return view('Claims.claim-request', compact('insurance_policies','selection_form','no_policies'));
    }

    public function getSelectionForm($insurance_policies, $OrganisationID = null, $InsuredName='', $CompanyName=''){
        return view('Claims.includes.claim-selection', compact('insurance_policies', 'InsuredName', 'CompanyName', 'OrganisationID'));
    }

    public function toList($items, $InsurancePolicyID, $PolicyTypeID, $OrganisationID){
        $to_return = [];
        foreach($items as $k=>$i){
            $to_return = "<option value='".$i->ClaimTypeID."' url='".route('claims-'.strtolower($i->Name).'-form', [$InsurancePolicyID, $PolicyTypeID, $OrganisationID])."'>".$i->DisplayText."</option>";
        }
        return $to_return;
    }

    public function getClaimTypes(Request $request){
        $data = $request->all();
        if(!isset($data['id1']) || !isset($data['id2']))
            return '';
        if(!$claim_type_ids = $this->claims->PolicyType_GetClaimTypeIDs($data['id2']))
            return '';
        $claim_types = [];
        foreach($claim_type_ids as $i){
            $claim_types[] = $this->claims->ClaimType_Get_first($i->ClaimTypeID);
        }
        return $this->toList($claim_types, $data['id1'], $data['id2'], $data['id3']);
    }

    protected function validator($data, $GST_flag){
        $validations = array (
            'ClaimTypeID' => 'required|min:1',
            'InsurancePolicyID' => 'required|min:1',
            //'Policy_Holder_Name'=>'max:70',
            //'Policy_Number',
            //'Claim_No' => 'min:1',
            //'Client_Contact_No',
            //'ABN',
            //'Excess_Amount',
            //'GST_Percentage',
            //'Additional_Contact_Name'=>'max:70',
            //'Contact_No'=>'max:30',
            'Date_and_Time_of_Event'=>'required|date_format:"d/m/Y H:i:s"',
            //'Description_of_Claim'=>''
            //'Insurance_Company'
            //'Additional_Comments'
            //'Confirmation',
            //'Terms_and_Conditions',
            //'Digital_SignatureAltContactName',
            //'Insurance_Contact_No', // underwriter contact no.?
        );
        if($GST_flag)
            $validations['GST_Percentage'] = 'numeric|nullable|min:1|between:0,99999.99';
        return Validator::make($data, $validations);
    }

    public function inquiryClaimForm($InsurancePolicyID, $ClaimTypeID, $OrganisationID, $ClientID = null){
        $policies = OrganisationHelper::getPolicyIDs($OrganisationID);
        if(!$policies)
            abort(404);
        if(!$ClientID)
            $ClientID = $this->client->getClientID();
        if(!$ClientID)
            abort(404);
        $client_infos = $this->claims->Client_Get_first($ClientID);
        $InsuredName = null;
        if($client_infos)
            $InsuredName = $client_infos->InsuredName;
        $insurance_policies = [];
        foreach($policies as $k=>$i){
            $policy = $this->claims->InsurancePolicy_Get_first($i->InsurancePolicyID);
            if(!$policy)
                abort(404);
            $claim_types = $this->claims->PolicyType_GetClaimTypeIDs($policy->PolicyTypeID);
            $policy_type = $this->claims->PolicyType_Get_first($policy->PolicyTypeID);
            if(count($claim_types)){
                $expiry_date = $policy->ExpiryDateTime?date("Y-m-d", strtotime($policy->ExpiryDateTime)):'';
                $display_text = $policy_type->DisplayText.' '.($policy->PolicyRefNum?('('.$policy->PolicyRefNum.(!empty($expiry_date)?' - '.$expiry_date:'').(!empty($InsuredName)?' - '.$InsuredName:'').')'):'');
                $insurance_policies[$i->InsurancePolicyID.'/'.$policy_type->PolicyTypeID] = $display_text;
            }
        }
        $policy = $this->policy->InsurancePolicy_Get_first($InsurancePolicyID);
        $PolicyTypeID = $policy->PolicyTypeID;
        $client = $this->claims->Client_Get_first($policy->ClientID);
        $insurance_quote = null;
        if($policy->InsuranceQuoteID)
            $insurance_quote = $this->claims->InsuranceQuote_Get_first($policy->InsuranceQuoteID);

        /*$client_id = $this->getClientID();
        $policy_ids = $this->getPolicyIDs($client_id);
        //$insurance_policies = [];
        $policies = [];
        $insurance_policies_minified = [];
        $policy = $this->policy->InsurancePolicy_Get_first($InsurancePolicyID);
        if(!property_exists($policy, 'InsurancePolicyID'))
            abort(404);
        $insurance_quote = $this->claims->InsuranceQuote_Get_first($policy->InsuranceQuoteID);
        $client = $this->claims->Client_Get_first($policy->ClientID);
        $InsuredName = $client->InsuredName;
        foreach($policy_ids as $i){
            $insurance_policy = $this->policy->InsurancePolicy_Get_first($i->InsurancePolicyID);
            $insurance_quote = $this->policy->InsuranceQuote_Get_first($insurance_policy->InsuranceQuoteID);
            $expiry_date = date("Y-m-d", strtotime($insurance_quote->ExpiryDateTime));
            $expiry_date_with_InsuredName = " - ".$expiry_date." {$InsuredName}";
            $policy_type = $this->policy->PolicyType_Get_first($insurance_policy->PolicyTypeID);//$rfq->PolicyTypeID);
            $policy_ref_num = $insurance_policy->PolicyRefNum;
            $policy_ref_num = (!empty($policy_ref_num)?"(":"").$policy_ref_num.$expiry_date_with_InsuredName.(!empty($policy_ref_num)?")":"");
            //$insurance_policies[$i->InsurancePolicyID] = ['DisplayText'=>$policy_type->DisplayText.'<br />'.$policy_ref_num, 'Description'=>$policy_type->Description,'PolicyTypeID'=>$policy_type->PolicyTypeID, 'PolicyNum'=>$insurance_policy->PolicyNum];
            $insurance_policies_minified[$i->InsurancePolicyID.'/'.$policy_type->PolicyTypeID] = $policy_type->DisplayText.' '.$policy_ref_num;
            $policies[$policy_type->PolicyTypeID] = $policy_type->DisplayText.' '.$policy_ref_num;
        }*/
        $claim_type_ids = $this->claims->PolicyType_GetClaimTypeIDs($PolicyTypeID);//$policy_type->PolicyTypeID);
        $claim_types = [];
        foreach($claim_type_ids as $claims){
            $claim_types[$claims->ClaimTypeID] = $this->claims->ClaimType_Get_first($claims->ClaimTypeID)->DisplayText;
        }
        $notes_list_form = view("Notes.list")->render();
        $client_contacts = $this->claims->Client_GetContactIDs_first($policy->ClientID);
        $contact = $this->claims->Contact_Get_first($client_contacts->ContactID);
        $underwriter = $this->claims->Underwriter_Get_first($policy->UnderwriterID);
        $insurable_business = $this->claims->InsurableBusiness_Get_first($client->InsurableBusinessID);
        $view_data = [
            'policyholdername' => $client->InsuredName,
            'policynum' => $policy->PolicyNum,
            'gst' => $insurable_business->IsRegisteredForGST,
            'excess' => $policy->Excess,
            'clientcontactno' => $contact->MobilePhoneNumber,
            'insurancecontactno' => $underwriter->PhoneNumber,
            'insurancecompany' => $underwriter->CompanyName
        ];
        
        $policy_policytype = $InsurancePolicyID."/".$PolicyTypeID;
        if(empty($insurance_policies))
            $insurance_policies[''] = 'N/A';
        return view('Claims.claim-inquiry', compact('claim_types','ClaimTypeID','insurance_policies','InsurancePolicyID', 'PolicyTypeID', 'OrganisationID', 'notes_list_form', 'policy_policytype','view_data'));
    }
    public function getOpenStatus(){
        $claim_statuses = $this->claims->ClaimStatus_GetClaimStatuses();
        foreach($claim_statuses as $i){
            $claim_status_name = strtolower($i->Name);
            $claim_status_name = trim($claim_status_name);
            if($claim_status_name == "new")
                return $i->ClaimStatusID;
        }
        return '';
    }

    private function createDataSession(Request $request, $data){
        $request->session()->put('claim_old_data', $data);
    }

    public function createInquiryClaim(Request $request){
        $client_id = $this->client->getClientID();
        $client = $this->client->Client_Get_first($client_id);
        $data = $request->all();
        $InsurancePolicyID = current(explode("/", $data['InsurancePolicyID']));
        $InsurancePolicy = $this->claims->InsurancePolicy_Get_first($InsurancePolicyID);
        if(!property_exists($InsurancePolicy,'InsurancePolicyID')){
            Flash::error(trans('messages.claim_failed_error'));
            $this->createDataSession($request, $data);
            return back();
        }
        $insurable_business = $this->claims->InsurableBusiness_Get_first($client->InsurableBusinessID);
        $data['GST'] = trim($insurable_business->IsRegisteredForGST);
        $gst_flag = $data['GST'] == 'Y'?true:false;
        if($this->validator($data, $gst_flag)->fails()){
            Flash::error(trans('messages.claim_incomplete_details'));
            $this->createDataSession($request, $data);
            $this->validator($data, $gst_flag)->validate();
            //return back();
        }
        $CurrentUserID = Auth::id();
        $ClaimStatusID = $this->getOpenStatus();
        $needed_fields = [
            'Claim_No',
            'GST_Percentage',
            'Additional_Contact_Name',
            'Contact_No',
            'Date_and_Time_of_Event',
            'Description_of_Claim',
            'Additional_Comments',
            'Confirmation',
            'Terms_and_Conditions',
            'Digital_Signature',
            'Insurance_Contact_No',
        ];
        foreach($needed_fields as $k=>$i){
            if(!array_key_exists($i, $data) || (array_key_exists($i, $data) && empty($data[$i])))
                $data[$i] = NULL;
        }
        $event_date_time = NULL;
        $IsDeclaredTrueByClaimant = NULL;
        $IsTermsAcceptedByClaimant = NULL;
        if($data['Date_and_Time_of_Event'] !== NULL){
            $event_date_time = date_create_from_format('d/m/Y H:i:s', $data['Date_and_Time_of_Event']);
            $event_date_time = date_format($event_date_time, 'Y-m-d H:i:s');
        }
        $claim_data = [
            $data['Claim_No'],
            $data['ClaimTypeID'],
            $InsurancePolicyID,
            $data['GST_Percentage'],
            $data['Additional_Contact_Name'],
            $data['Contact_No'],
            $event_date_time,
            $data['Description_of_Claim'],
            $data['Additional_Comments'],
            strtolower(trim($data['Confirmation'])) == "on"?"Y":"N",
            strtolower(trim($data['Terms_and_Conditions'])) == "on"?"Y":"N",
            $data['Digital_Signature'],
            $data['Insurance_Contact_No'],
            $ClaimStatusID,
            $CurrentUserID
        ];
        $res = $this->claims->Claim_Create_first($claim_data, ['ClaimID'=>'uniqueidentifier']);
        if(!property_exists($res, 'ClaimID')){
            Flash::error(trans('messages.claim_failed_error'));
            $this->createDataSession($request, $data);
            return back();
        }
        $claim_id = $res->ClaimID;
        $document_types = $this->claims->DocumentType_GetDocumentTypes();
        $document_type_id = null;
        foreach($document_types as $i){
            $type_name = $i->Name;
            $type_name = trim($type_name);
            $type_name = strtolower($type_name);
            if($type_name == "other"){
                $document_type_id = $i->DocumentTypeID;
                break;
            }
        }
        if(count($request->files)){
            $res = Attachment::capture($request, $claim_id, 'Claim', $document_type_id, $CurrentUserID);
            if(!$res){
                Flash::error(trans('messages.file_attachment_upload_fail'));
                $this->createDataSession($request, $data);
                return back();
            }
        }
        $task = new TasksController($this->policy);
        $task_types = $this->claims->TaskType_GetTaskTypes();
        $task_type_id = null;
        $task_type_text = '';
        foreach($task_types as $i){
            if(strtolower(trim($i->Name)) == 'claim'){
                $task_type_id = $i->TaskTypeID;
                $task_type_text = $i->DisplayText;
            }
        }
        $statuses = $this->claims->TaskStatus_GetTaskStatuses();
        $open_status_id = '';
        foreach($statuses as $k=>$i){
            if(strtolower(trim($i->Name)) == 'new')
                $open_status_id = $i->TaskStatusID;
        }
        $task_arr = [
            'ParentID'=>$claim_id,
            'EntityName'=>'Claim',
            'OrganisationID'=>$data['OrganisationID'],
            'organisation_role_id'=>null,
            'assigned_to'=>null,
            'task_request'=>$task_type_id,
            'subject'=>$task_type_text,
            'message_details'=>'',
            'due_date'=>null,
            $open_status_id,
            $CurrentUserID,
        ];
        $res = $task->ajaxSave($task_arr, false);
        if(!$res){
            Flash::error(trans('messages.claim_failed_error'));
            $this->createDataSession($request, $data);
            return back();
        }
        $res = $this->claims->Claim_Lodge_first([$claim_id, Auth::id()]);

        if(!property_exists($res, "return_value") || (property_exists($res, "return_value") && $res->return_value != "0")){
            Flash::error(trans('messages.claim_failed_error'));
            $this->createDataSession($request, $data);
            return back();
        }
        $request->session()->forget('claim_old_data');
        Flash::success(trans('messages.claim_success_save'));
        return redirect(route('claim-request'));
    }

    public function motorVehicleClaimForm($InsurancePolicyID, $ClaimTypeID, $ClientID = null){
        $policies = OrganisationHelper::getPolicyIDs();
        if(!$policies)
            abort(404);
        if(!$ClientID)
            $ClientID = $this->client->getClientID();
        if(!$ClientID)
            abort(404);
        $client_infos = $this->claims->Client_Get_first($ClientID);
        $InsuredName = null;
        if($client_infos)
            $InsuredName = $client_infos->InsuredName;
        $insurance_policies = [];
        foreach($policies as $k=>$i){
            $policy = $this->claims->InsurancePolicy_Get_first($i->InsurancePolicyID);
            if(!$policy)
                abort(404);
            $claim_types = $this->claims->PolicyType_GetClaimTypeIDs($policy->PolicyTypeID);
            $policy_type = $this->claims->PolicyType_Get_first($policy->PolicyTypeID);
            if(count($claim_types)){
                $expiry_date = $policy->ExpiryDateTime?date("Y-m-d", strtotime($policy->ExpiryDateTime)):'';
                $display_text = $policy_type->DisplayText.' '.($policy->PolicyRefNum?('('.$policy->PolicyRefNum.(!empty($expiry_date)?' - '.$expiry_date:'').(!empty($InsuredName)?' - '.$InsuredName:'').')'):'');
                $insurance_policies[$i->InsurancePolicyID.'/'.$policy_type->PolicyTypeID] = $display_text;
            }
        }
        $policy = $this->policy->InsurancePolicy_Get_first($InsurancePolicyID);
        $PolicyTypeID = $policy->PolicyTypeID;
        $client = $this->claims->Client_Get_first($policy->ClientID);
        $insurance_quote = null;
        if($policy->InsuranceQuoteID)
            $insurance_quote = $this->claims->InsuranceQuote_Get_first($policy->InsuranceQuoteID);

        /*$client_id = $this->client->getClientID();
        $policy_ids = $this->getPolicyIDs($client_id);
        //$insurance_policies = [];
        $policies = [];
        $insurance_policies_minified = [];
        $policy = $this->policy->InsurancePolicy_Get_first($InsurancePolicyID);
        $insurance_quote = $this->claims->InsuranceQuote_Get_first($policy->InsuranceQuoteID);
        $client = $this->claims->Client_Get_first($policy->ClientID);
        $InsuredName = $client->InsuredName;
        foreach($policy_ids as $i){
            $insurance_policy = $this->policy->InsurancePolicy_Get_first($i->InsurancePolicyID);
            $insurance_quote = $this->policy->InsuranceQuote_Get_first($insurance_policy->InsuranceQuoteID);
            $expiry_date = date("Y-m-d", strtotime($insurance_quote->ExpiryDateTime));
            $expiry_date_with_InsuredName = " - ".$expiry_date." {$InsuredName}";
            $policy_type = $this->policy->PolicyType_Get_first($insurance_policy->PolicyTypeID);//$rfq->PolicyTypeID);
            $policy_ref_num = $insurance_policy->PolicyRefNum;
            $policy_ref_num = (!empty($policy_ref_num)?"(":"").$policy_ref_num.$expiry_date_with_InsuredName.(!empty($policy_ref_num)?")":"");
            $insurance_policies[$i->InsurancePolicyID] = ['DisplayText'=>$policy_type->DisplayText.'<br />'.$insurance_policy->PolicyNum, 'Description'=>$policy_type->Description,'PolicyTypeID'=>$policy_type->PolicyTypeID, 'PolicyNum'=>$insurance_policy->PolicyNum];
            //$insurance_policies_minified[$i->InsurancePolicyID.'/'.$policy_type->PolicyTypeID] = $policy_type->DisplayText." {$policy_ref_num}";
            $policies[$policy_type->PolicyTypeID] = $policy_type->DisplayText." {$policy_ref_num}";
        }*/
        $claim_type_ids = $this->claims->PolicyType_GetClaimTypeIDs($policy_type->PolicyTypeID);
        $claim_types = [];
        foreach($claim_type_ids as $claims){
            $claim_types[$claims->ClaimTypeID] = $this->claims->ClaimType_Get_first($claims->ClaimTypeID)->DisplayText;
        }
        $notes_list_form = view("Notes.list")->render();
        //$policy = $this->policy->InsurancePolicy_Get_first($InsurancePolicyID);
        $PolicyTypeID = $policy->PolicyTypeID;
        $policy_policytype = $InsurancePolicyID."/".$PolicyTypeID;
        if(empty($insurance_policies_minified))
            $insurance_policies_minified[''] = 'N/A';
        return view('Claims.claim-motor-vehicle', compact('claim_types','ClaimTypeID','insurance_policies_minified','InsurancePolicyID', 'PolicyTypeID', 'notes_list_form', 'policy_policytype'));
    }

    public function createMotorVehicleClaim(Request $request){
        $data = $request->all();
        dd($data);
    }

    private function getOrganisationIDByContactID(){
        $contact_id = Auth::user()->contact_id;
        $organisation_id = null;
        $organisations = $this->claims->Organisation_GetOrganisations();
        /*foreach($organisations as $org){
            $org_contacts = $this->claims->Oganisation_GetContacts($org->OrganisationID);
            foreach($org_contacts as $contact){
                if($contact->ContactID == $contact_id){
                    $organisation_id = ['OrganisationID'=>$org->OrganisationID,'DisplayText'=>$org->Name];
                }
            }
        }*/
        $org_infos = $this->claims->Contact_GetOrganisations_first($contact_id);
        foreach($organisations as $i){
            if($i->OrganisationID == $org_infos->OrganisationID)
                $organisation_id = ['OrganisationID'=>$i->OrganisationID,'DisplayText'=>$i->Name];
        }
        if(empty($organisation_id))
            abort(404);
        return array($organisations, $organisation_id);
    }

    private function getInsurancePoliciesByOrganisation($OrganisationID = null){
        if(empty($OrganisationID))
            return null;
        $insurance_policies = [];
        $policy_ids = $this->claims->Organisation_GetPolicyIDs($OrganisationID);
        if(!$policy_ids)
            return '';
        foreach($policy_ids as $i){
            $insurance_policy = $this->claims->InsurancePolicy_Get_first($i->InsurancePolicyID);
            $policy = $this->claims->PolicyType_Get_first($insurance_policy->PolicyTypeID);
            $insurance_policies[$insurance_policy->InsurancePolicyID] = $policy->DisplayText;
        }
        return $insurance_policies;
    }

    public function getInsurancePoliciesRenderView($OrganisationID = null, $insurance_policy_id = null, $client_id = null, Request $request = null){
        $data = null;
        if($OrganisationID == null && $request !== null){
            $data = $request->all();
            if(isset($data['Organisation']))
                $OrganisationID = $data['Organisation'];
            else
                return ''; 
        }
        if(empty($client_id))
            $client_id = $this->client->getClientID();
        //$insurance_policies = $this->getInsurancePoliciesByOrganisation($OrganisationID);
        $claim_ids = $this->claims->Client_GetClaimIDs($client_id);
        if(empty($claim_ids))
            abort(404);
        $policy_types = [];

        foreach($claim_ids as $i){
            $claim = $this->claims->Claim_Get_first($i->ID);
            $insurance_policy_infos = $this->claims->InsurancePolicy_Get_first($claim->InsurancePolicyID);
            $policy_type = $this->claims->PolicyType_Get_first($insurance_policy_infos->PolicyTypeID);
            $insurance_policies[$claim->InsurancePolicyID] = $policy_type->DisplayText;
        }
        return view('Claims.History.policy-select', compact('insurance_policies', 'OrganisationID', 'insurance_policy_id'))->render();
    }

    public function getHistory($OrganisationID = null, $InsurancePolicyID = null){
        $client_id = $this->client->getClientID();
        if(!$client_id)
            return view('Claims.History.list', ['no_policies'=>true]);
        $no_policies = false;
        $Organisations = null;
        if(empty($OrganisationID)){
            $Organisation_infos = $this->getOrganisationIDByContactID();
            list($Organisations, $OrganisationID) = $Organisation_infos;
        }else
            $Organisations = $this->claims->Organisation_GetOrganisations();
        $organisations_with_claims = [];
        foreach($Organisations as $i){
            $org_claim_ids = $this->claims->Organisation_GetClaimIDs($i->OrganisationID);
            $client_claim_ids = $this->claims->Client_GetClaimIDs($client_id);
            if($org_claim_ids){
                foreach($org_claim_ids as $j){
                    foreach($client_claim_ids as $h){
                        if($h->ID == $j->ID){
                            $organisations_with_claims[] = $i;
                            break 2;
                        }
                    }
                }
            }
        }
        $OrganisationID = current($organisations_with_claims)->OrganisationID;//$OrganisationID['OrganisationID'];
        $policy_select_rendered = $this->getInsurancePoliciesRenderView($OrganisationID,$InsurancePolicyID, $client_id);
        $current_claims = [];
        $all_claims = [];
        $claim_ids = [
            'current_claims' => $this->claims->Client_GetCurrentClaimIDs($client_id),
            'all_claims' => $this->claims->Client_GetClaimIDs($client_id)
        ];
        foreach($claim_ids as $k=>$i){
            foreach($i as $j){
                $claim = $this->claims->Claim_Get_first($j->ID);
                $policy = $this->policy->InsurancePolicy_Get_first($claim->InsurancePolicyID);
                //$insurance_quote = $this->claims->InsuranceQuote_Get_first($policy->InsuranceQuoteID);
                $underwriter = $this->claims->Underwriter_Get_first($policy->UnderwriterID);
                $consultant_name = ($claim->CreatedBy == $claim->LodgedByUserID || empty($claim->LodgedByUserID)?'None':$claim->LodgedByUserID);
                $consultant_name = ($consultant_name != 'None'?$this->claims->Contact_GetByUserID_first($claim->LodgedByUserID)->PreferredName:$consultant_name);
                array_push($$k, [
                    'Reference_Number' => $claim->ClaimRefNum,
                    'Date_Lodged' => (!empty($claim->LodgementDateTime)?date("Y-m-d", strtotime($claim->LodgementDateTime)):$claim->LodgementDateTime),
                    'Insurance_Company' => $underwriter->CompanyName,
                    'Insurance_Name' => $this->claims->PolicyType_Get_first($policy->PolicyTypeID)->Name,
                    'Consultant_Name' => $consultant_name,
                    'Status' => $this->claims->ClaimStatus_Get_first($claim->ClaimStatusID)->DisplayText,
                    //'Excess' => $insurance_quote->Excess,
                    'Excess' => $policy->Excess,
                    'Claimed_Amount' => $policy->Premium,
                    'Policy_Number' => $policy->PolicyRefNum,
                    'Consultant_Number' => $claim->UnderwriterPhoneNumber
                ]);
            }
        }
        return view('Claims.History.list', compact('organisations_with_claims', 'policy_select_rendered', 'current_claims','all_claims','no_policies'));
    }

    /*orly*/
    public function finalizedClaimsIndex(PolicyHelper $ph, $OrganisationID = "DE09F4B6-C708-4F5F-A48E-432AF31E4D74"){
        $ClientID = Auth::id();
        $getOrgIDs = $ph->Organisation_GetOrganisations(); /* get all org ids*/
        $current_claims = [];
        $claims = [];

        // foreach( $getOrgIDs as $key => $value ){
        //     $getOrgCurrentClaims = $ph->Organisation_GetCurrentClaimIDs($value->OrganisationID);
            
        //     foreach( $getOrgCurrentClaims as $a => $b){
        //         $getCurrentClaim = $ph->Claim_Get_first( $b->ID );
        //         $getPolicy = (object) head(Entity::getMultiple('InsurancePolicy', $getCurrentClaim->InsurancePolicyID, 3));

        //         $current_claims[] = array(
        //             'claim' => $getCurrentClaim,
        //             'policy_details' => $getPolicy
        //             );

        //         $claims [] = array(
        //                         $getCurrentClaim->ClaimRefNum,
        //                         $getCurrentClaim->LodgementDateTime,
        //                         $getPolicy->PolicyNum,
        //                         $getPolicy->Client["InsuredName"],
        //                         $getCurrentClaim->ClaimNum,
        //                         $getPolicy->Underwriter["CompanyName"],
        //                         $getPolicy->Product,
        //                         $getPolicy->InsuranceQuote["Excess"]
        //                     );
                
        //         // dd($current_claims);
        //         // dd($claims);
        //     }

        // }

        // dd($claims);

        return view( 'Claims.finalized-claims', compact('ClientID') );

    }

    public function getFinalizedClaims(PolicyHelper $ph){
        // $str = '{"data": [ { "name": "Tiger Nixon", "position": "System Architect", "salary": "$320,800", "start_date": "2011/04/25", "office": "Edinburgh", "extn": "5421" }, { "name": "Garrett Winters", "position": "Accountant", "salary": "$170,750", "start_date": "2011/07/25", "office": "Tokyo", "extn": "8422" } ] }';

        // return json_encode($str);
        $getOrgIDs = $ph->Organisation_GetOrganisations(); /* get all org ids*/
        $current_claims = [];
        $claims = [];

        foreach( $getOrgIDs as $key => $value ){
            $getOrgCurrentClaims = $ph->Organisation_GetCurrentClaimIDs($value->OrganisationID);

            foreach( $getOrgCurrentClaims as $a => $b){
                $getCurrentClaim = $ph->Claim_Get_first( $b->ID );
                
                $getPolicy = (object) head(Entity::getMultiple('InsurancePolicy', $getCurrentClaim->InsurancePolicyID, 3));

                $current_claims[] = array(
                    'claim' => $getCurrentClaim,
                    'policy_details' => $getPolicy
                    );

                $claims[] = array(
                                'DT_RowId' => 'ClaimID-' . $getCurrentClaim->ClaimID,
                                $getCurrentClaim->ClaimRefNum,
                                $getCurrentClaim->LodgementDateTime,
                                $getPolicy->PolicyNum,
                                $getPolicy->Client["InsuredName"],
                                $getCurrentClaim->ClaimNum,
                                $getPolicy->Underwriter["CompanyName"],
                                $getPolicy->Product,
                                $getPolicy->InsuranceQuote["Excess"]
                                );
                // dd($claims);
                // dd($current_claims);
                // dd($claims);
            }

        }

        $response = array();
        $response['success'] = true;
        $response['aaData'] = $claims;
        echo json_encode($response);
    }

}

?>