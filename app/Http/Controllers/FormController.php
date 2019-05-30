<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FormHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Task;

class FormController extends Controller
{
    var $fh;
    var $policy_type = '';
    var $rfq_id = '';
    var $RFQ_details;
    var $questions = [];
    var $answers = [];

    public function __construct() {
        $this->fh = new FormHelper();

    }
   
    public function index(FormHelper $fh, $rfq_id){
        //get rfq details using rfqid $rfq_id
        $rfq_id = $rfq_id;
        $rfq = $this->getRFQDetails($rfq_id);
        $this->getContactDetails();
        $t = [];
       
        //set policy type id 
        $this->setPolicyTypeID($rfq->PolicyTypeID);

        //get all questions
        $get_questions = $this->getRFQQuestionsByPolicyTypeID( $this->policy_type );
      
            //next, get all answer based on rfqid and policy type id
            foreach($get_questions as $key => $value){
                if( !isset( $value->QuestionName ) ) continue;
                    //populate 
                    $this->questions[$value->QuestionName] = "";
                    // $this->answers[] = " ";
                    $this->getAnswerByQuestionName( $rfq_id, $value->QuestionName );

            }
        
        $this->generateCSV();

    }

    public function getRFQDetails($rfq_id){
        $this->RFQ_details = $this->fh->RFQ_Get_first($rfq_id);
        
        return $this->fh->RFQ_Get_first($rfq_id);

    }

    public function getRFQQuestionsByPolicyTypeID($policy_id){
        return $this->fh->GetRFQQuestionsByPolicyTypeID( $policy_id );

    }

    public function getAnswerByQuestionName($rfq_id, $question_name){
        $answer = $this->fh->RFQ_GetFormQuestionAnswersByQnName( [$rfq_id, $question_name] );
        // $this->questions[$question_name] = $answer;
        $this->questions[$question_name] = array('answer' => array(), 'details' => $answer);

        foreach($answer as $key => $value){
            //answer has differed type
            if( isset($value->FormQuestionTypeName) ){

                switch ($value->FormQuestionTypeName) {

                    case 'Address':
                        $this->questions[$question_name]['answer'][] = $this->fh->Address_Get_first( $value->AnswerAddressID );
                        
                        break;

                    case 'SelectMulti':
                    case 'SelectOne':
                        $this->questions[$question_name]['answer'][] = $this->fh->FormQuestionPossChoice_Get_first( $value->FormQuestionPossChoiceID );

                        break;

                    case 'Number':
                        $this->questions[$question_name]['answer'][] = $value->AnswerNumber;

                        break;

                    case 'Boolean':
                        $this->questions[$question_name]['answer'][] = $value->AnswerBoolean;

                        break;

                    case 'Text': 
                        $this->questions[$question_name]['answer'][] = $value->AnswerText;

                        break;

                    case 'Date':
                        $this->questions[$question_name]['answer'][] = $value->AnswerDateTime;

                        break;

                    default:
                       
                        break;
                }

            }
            else{

                $this->answer[] = ' ';

            }
        }

    }

    public function setPolicyTypeID($id){
        $this->policy_type = $id;
    
    }

    public function setRFQID($id){
        $this->rfq_id = $id;

    }

    public function getPolicyTypeID(){
        return $this->policy_type;

    }

    public function getRFQID(){
        return $this->rfq_id;

    }
 
    

    public function getContactDetails(){ 
        
        //if no contact ID get name from Lead_Get
        if(isset( $this->RFQ_details->ContactID )){
            $client_id = $this->fh->Contact_GetClientIDs_first( $this->RFQ_details->ContactID );
            $contact_details = $this->fh->Contact_Get_first( $this->RFQ_details->ContactID );
            $contact_person = $contact_details->FirstName . " " . $contact_details->Surname;
            $email_address = $contact_details->EmailAddress;
            $phone_number = $contact_details->MobilePhoneNumber;
        }
        else{
            $contact_details = $this->fh->Lead_Get_first( $this->RFQ_details->LeadID );
            $contact_person = $contact_details->Name;
            $email_address = $contact_details->EmailAddress;
            $phone_number = $contact_details->PhoneNumber;
        }

        if(isset($client_id->ClientID)){
            $client_details = $this->fh->Client_Get_first( $client_id->ClientID );
            $insurance_details = $this->fh->InsurableBusiness_Get_first( $client_details->InsurableBusinessID );
            $business_structure = $this->fh->BusinessStructureType_Get_first( $insurance_details->BusinessStructureTypeID );
        }

        $details = array(
                    'ContactPerson' => $contact_person,
                    'EmailAddress' => $email_address,
                    'MobilePhoneNumber' => $phone_number,
                    'BusinessStructure' => isset($business_structure->DisplayText) ? $business_structure->DisplayText : '-',
                    'InsuredName' => isset($client_details->InsuredName) ? $client_details->InsuredName : '-',
                    'TradingName' => isset($insurance_details->TradingName) ? $insurance_details->TradingName : '-',
                    'AustralianBusinessNumber' => isset($insurance_details->AustralianBusinessNumber) ? $insurance_details->AustralianBusinessNumber : '-',
                    'IsRegisteredForGST' => isset($insurance_details->IsRegisteredForGST) ? $insurance_details->IsRegisteredForGST : '-'
                    );
        
        return $details;

    }

    public function generateCSV(){
        $question = $this->questions;
        $row1 = [];
        $row2 = [];
        $contact_details = $this->getContactDetails();
        $f = [];

        // dd($this->RFQ_details);
        $rfq_fields = ['RFQRefNum', 'CreatedDateTime', 'RFQStatusID', 'LodgementDateTime'];

            foreach($rfq_fields as $key => $value){
                $row1[] = $value;
                
                switch($value){

                    case 'RFQStatusID':
                        $row2[] = $this->fh->RFQStatus_Get_first($this->RFQ_details->$value)->Name;

                    break;

                    default: 
                        $row2[] = $this->RFQ_details->$value;

                    break;

                }
                
            }
            // dd($row2);
            foreach($contact_details as $key => $value){
                $row1[] = $key;
                $row2[] = $value;

            }

            foreach($question as $key => $value){
                $i = "";

                foreach( $value['details'] as $a => $b ){
                    $row1[] = $key . "" . $i++;
                    // $row2[] = $a;

                     if( isset($b->FormQuestionTypeName) ){
                        // $row2[] = $value['answer'][$a];
                        switch($b->FormQuestionTypeName){
                        
                            case 'Address':
                                $row2[] = '';
                                //$field = ['UnitNumber', 'StreetNumber', 'StreetName', 'City', 'State', 'Postcode', 'Country'];
                                $field = ['AddressLine1', 'AddressLine2', 'City', 'State', 'Postcode', 'Country'];
                                
                                    foreach($field as $i){
                                        $row1[] = $i;
                                        $row2[] = $value['answer'][$a]->$i;

                                    }

                                break;

                            case 'SelectMulti':
                            case 'SelectOne':
                                $row2[] = $value['answer'][$a]->DisplayText;
                                
                                break;

                            case 'Number':
                                $row2[] = $value['answer'][$a];
     
                                break;

                            case 'Boolean':
                                $row2[] = $value['answer'][$a];

                                break;

                            case 'Text': 
                                $row2[] = $value['answer'][$a];

                                break;

                            case 'Date':
                                $row2[] = $value['answer'][$a];

                                break;

                            default:
                                $row2[] = '';
                                break;
                        
                        }

                    }
                    else{
                        $row2[] = '--';

                    }

                }

            }

        // dd($row2);

         // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=data.csv');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        fputcsv($output, $row1);
        fputcsv($output, $row2);

    }

    public function nonProceed( $rfq_id ){
        //$this->fn->SPNONPROCEED( $rfq_id );
        return true;

    }

}


