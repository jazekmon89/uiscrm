<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ClientHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\Providers\Facades\Entity;


class LeadController extends Controller
{
   

    public function __construct() {

        $this->middleware("auth");

    }

    public function index(ClientHelper $ClientHelper, Request $request) {
        
        $getRFQ = $this->getRFQIds($ClientHelper, 'DE09F4B6-C708-4F5F-A48E-432AF31E4D74');

        return view( 'Lead.lead' );



    }

    public function getRFQIds(ClientHelper $ch, $organization_id = 'DE09F4B6-C708-4F5F-A48E-432AF31E4D74'){
      
        $getRFQs = (array) $ch->Organisation_GetRFQIDs( $organization_id ) ;

        $id = [];

        foreach($getRFQs as $key){

                $id[] = $this->getRFQdetails( $ch, $key->RFQID);

        }


    }

    public function getRFQdetails(ClientHelper $ch, $id){

        $rfq_detail = (object) $ch->RFQ_Get_first( $id );
        // dd($rfq_detail);
        $info = "";
        
        if( isset($rfq_detail->LeadID) ){

            $data = (object) Entity::get('RFQ', $id, 3);
           
            // dd($data);
            
            $info = array(
                'OAuthProviderName' => null,
                'OAuthUserIdent' => null,
                'FirstName' => $data->Lead['Name'],
                'MiddleNames' => null,
                'LastName' => "",
                'PrefferedName' => $data->Lead['Name'],
                'HomeAddressID' => null,
                'PostalAddressID' => null,
                'EmailAddress' => $data->Lead['EmailAddress'],
                'MobilePhoneNumber' => $data->Lead['PhoneNumber'],
                'BirthDate' => null,
                'BirthCountry' => null,
                'BirthCity' => null,
                'InsuredName' => $data->InsuredName,
                'InsuredBusinessID' => (isset($data->InsurableBusinessID) ? $data->InsurableBusinessID : ''),

            );

        // dd( $info );

        }

    }

}

