<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ClientHelper;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\Auth;

use App\Task;

class PolicyDetailsController extends Controller
{
    public function __construct() {
        $this->middleware("auth");
    }
    public function index(ClientHelper $ClientHelper, Request $request, Task $task) {
        $policies = [];
        $policies2 = [];
        

        // dd(Auth::user()->contact_id);
        //$contact = head((array)$ClientHelper->getContacts());
        $client_id = (array)$ClientHelper->getClientID();
       
        //foreach($clients as $client){
            //get clients policies
            $get_policies = (array) $ClientHelper->getCurrentPolicies($client_id, false);
           
            foreach($get_policies as $policy){
                $InsurancePolicyID = $policy["InsurancePolicyID"];
                
                $r = (array) $ClientHelper->InsurancePolicy_Get_first($InsurancePolicyID);
                
                $p = (array) $ClientHelper->PolicyType_Get_first($r["PolicyTypeID"]);

                $policies2[ $p["DisplayText"] ] = array( "InsurancePolicyID"=>$InsurancePolicyID);
                              
                $attachments = (array) $ClientHelper->FileAttachment_GetFileAttachmentsByParentID($InsurancePolicyID);
                //dd($attachments);
                foreach($attachments as $attachment){
                   
                    $get_data_type = head((array) $ClientHelper->FileAttachment_Get($attachment->FileAttachmentID));
                
                    if($get_data_type->DocumentTypeID == "0F956461-C2A1-E611-902E-000C292D0644"){
                        $policies[$attachment->FileAttachmentID] = array("DisplayText"=>$p["DisplayText"], "InsurancePolicyID"=>$InsurancePolicyID, "PolicyRefNum"=>$policy["PolicyRefNum"]);
                       
                    }    
                }

            }   
        //}

        return view( 'PolicyDetails.Client', compact('policies', 'policies2') );

    }

    public function downloadCoC($attachment_id, ClientHelper $ClientHelper, Request $request){
        $attachment_id = $attachment_id;
        $attachment_id = explode(":", $attachment_id);
        $filename = $attachment_id[1];
        //$attachments = (array) $ClientHelper->FileAttachment_GetFileAttachmentsByParentID($InsurancePolicyID);
        //foreach($attachments as $attachment){
            $get_data_type = head( (array) $ClientHelper->FileAttachment_Get( $attachment_id[0] ) );

                
            if($get_data_type->DocumentTypeID == "0F956461-C2A1-E611-902E-000C292D0644"){
                                header("Content-Disposition: attachment; filename=$filename.pdf");
                                header("Content-type: application/pdf");
                                $data = $get_data_type->FileData;
                                echo base64_decode($data);
            }
  
    }

    public function amend(Request $request, Task $task){
        $data = $request->all();
        $CurrentUserID = Auth::id();
        
        $arr = [
                $data["policy_id"], //ParentID
                "InsurancePolicy",                      //EntityName
                "DE09F4B6-C708-4F5F-A48E-432AF31E4D74", //OrganisationID
                null,                                     //AssignToOrganisationRoleID
                null,                                     //AssignToUserID
                "1B956461-C2A1-E611-902E-000C292D0644", //TaskTypeID
                "SpecificRequest",                              //Subject
                $data["message_details"],                          //Description
                null,                                   //DueDate
                "1C956461-C2A1-E611-902E-000C292D0644’ ", //TaskStatusID
                $CurrentUserID                           //CurrentUserID
                ];

        return $task->Task_CreateByTypeName($arr, ['TaskID'=>'uniqueidentifier']);

    }

}

