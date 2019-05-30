<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Helpers\SearchHelper;
use App\Helpers\ClientHelper;
use App\Providers\Facades\Entity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\NotesController;

use Carbon\Carbon;

class ClientController extends Controller
{
    var $tasks;

	public function showContacts(ClientHelper $ClientHelper, $ClientID=null){
        $Client['Client'] = Entity::getMultiple('Contact', Entity::model()->Client_GetContactIDs($ClientID), 2);
        $c = $ClientHelper->Client_Get_first($ClientID); 
        $exclusions_address = ['AddressID','CreatedDateTime','CreatedBy','ModifiedBy','ModifiedDateTime'];
        $Client['InsuredName'] = $c->InsuredName;
        $Client['ABN'] = $ClientHelper->InsurableBusiness_Get_first($c->InsurableBusinessID)->AustralianBusinessNumber;
        
        $clients_contact = $ClientHelper->Client_GetContactIDs($ClientID);
        $contacts = [];
        foreach( $clients_contact as $a => $b ){
            $get_contact = $ClientHelper->Contact_Get($b->ContactID);
            foreach($get_contact as $c => $d){
                $address = null;
                if($d->HomeAddressID)
                    $address = current($this->createAddressText($exclusions_address, $ClientHelper->Address_Get_first($d->HomeAddressID)));
                if($d->PostalAddressID)
                    $address = current($this->createAddressText($exclusions_address, $ClientHelper->Address_Get_first($d->PostalAddressID)));
                $contacts[$b->ContactID] = array(
                    "UserID" => $d->UserID,
                    "FirstName" => $d->FirstName,
                    "MiddleNames" => $d->MiddleNames,
                    "Surname" => $d->Surname,
                    "PreferredName" => $d->PreferredName,
                    "HomeAddress" => $address,
                    "PostalAddressID" => $d->PostalAddressID,
                    "EmailAddress" => $d->EmailAddress,
                    "MobilePhoneNumber" => $d->MobilePhoneNumber,
                    "BirthDate" => $d->BirthDate,
                    "BirthCity" => $d->BirthCity,
                    "BirthCountry" => $d->BirthCountry,
                    "ContactRefNum" => $d->ContactRefNum,
                    "CreatedBy" => $d->CreatedBy,
                    "CreatedDateTime" => $d->CreatedDateTime,
                    "ModifiedBy" => $d->ModifiedBy,
                    "ModifiedDateTime" => $d->ModifiedDateTime
                );
            }
        }
        return view("Client.Profiles.contact", compact( 'contacts', 'Client', 'ClientID' ));
    }

    public function searchProfiles(SearchHelper $Helper, Request $request) {
        $business_fields  = ['InsuredName', 'TradingName'];
        $contact_fields   = ['MobilePhoneNumber', 'EmailAddress', 'ModifiedDate'];
        $insurance_fields = ['PolicyNum'];

        $items = [];

        // we tell SearchHelper to include sub Objects
        $request->includes = ['contacts', 'clients'];
        if ($params = $request->intersect($business_fields)) 
        {
            $request->replace($params);
            $clients = $Helper->search('FindClientByClientAndBusinessDetails');

            foreach($clients as $client) 
            {
                if (!empty($client['Contacts'])) 
                {
                    foreach($client['Contacts'] as $contact) 
                    {
                        $_client = $client;
                        $_client['Contact'] = $contact;

                        // we clean contacts to avoid confusion
                        unset($_client['Contacts']);

                        // 1 row per client    
                        if (!arr_lfind($items, "ClientID", $client['ClientID']))
                            $items[] = $_client;
                    }
                }
            }
        }
        if ($params = $request->intersect($contact_fields)) {
            $request->replace($params);
            $contacts = (array)$Helper->search('FindContactByPersonalDetails');

            foreach($contacts as $contact) 
            {
                if (!empty($contact['Clients'])) 
                {
                    $clients = array_pull($contact, 'Clients');
                    foreach($clients as $client) 
                    {
                        $rec = arr_lkey($items, "ClientID", $client['ClientID']);
                        if ($rec !== false) {
                            array_set($items, "$rec.Contact", $contact);
                        }
                        else {
                            $client['Contact'] = $contact;     
                            $items[] = $client;
                        }
                    }   
                }
            }
        }
        if ($params = $request->intersect($insurance_fields)) 
        {
            $request->replace($params);
            $entities = (array)$Helper->search('FindInsuranceEntitiesByInsuranceDetails');

            foreach($entities as $entity) 
            {
                if ($entity['type'] === 'InsurancePolicy') 
                {
                    if (!$Client = array_get($entity, 'Client')) 
                        continue;

                    if (!empty($Client['InsurableBusinessID'])) {
                        $Client['InsurableBusiness'] = Entity::get('InsurableBusiness', $Client['InsurableBusinessID']);
                    }

                    $rec = arr_lkey($items, "ClientID", $entity['ClientID']);
                    if ($rec !== false && $Client) {
                        array_set($items, "$rec.InsurancePolicy", $entity);
                    }
                    else {
                        array_set($Client, "InsurancePolicy", $entity);
                        $items[] = $Client;
                    }
                }
            }
        }
        foreach($items as &$item) 
        {
            if (!array_get($item, "InsurancePolicy")) 
            {
                $policies = Entity::getMultiple("InsurancePolicy", $Helper->Client_GetPolicyIDs($item['ClientID']));
                foreach($policies as $policy) 
                {
                    if (!$request->PolicyNum && $policy['PolicyNum'] || $request->PolicyNum === $policy['PolicyNum']) {
                        $item["InsurancePolicy"] = $policy;
                        break;
                    }
                }
            }
            if (!array_get($item, "InsurableBusiness")) 
            {
                $insurable_business = $Helper->InsurableBusiness_Get_first($item['InsurableBusinessID']);
                if (!$request->InsurableBusiness && $insurable_business 
                || $request->InsurableBusiness === $insurable_business) 
                {
                    $item["InsurableBusiness"] = (array)$insurable_business;
                    $item["InsurableBusiness"]['BusinessStructureType'] = $Helper->BusinessStructureType_Get_first($insurable_business->BusinessStructureTypeID)->Name;
                }
            }
        }
        
        return response()->json($items);
    }

    public function stripOffExcludedFields($excl, $rename, $prefix, $data){
        $data = get_object_vars($data);
        $to_return = [];
        foreach($data as $k=>$i){
            if(!in_array($k, $excl)){
                $col_name = in_array($k, $rename)?$prefix.$k:$k;
                $to_return[] = $col_name;
            }
        }
        return $to_return;
    }

    public function getUserName($UserID, $ClientHelper){
        $contact = $ClientHelper->Contact_GetUserByID_first($UserID);
        $fields = ['FirstName', 'MiddleNames', 'Surname'];
        $to_return = '';
        foreach($fields as $i){
            if(!empty($contact->$i))
                $to_return .= $contact->$i.' ';
        }
        return trim($to_return);
    }

    public function getRecordsWithCustomHeaders($headers, $prefix, $renamed_fields, $user_fields, $data, $ClientHelper){
        $to_return = [];
        foreach($headers as $i){
            $field_name = $i;
            foreach($renamed_fields as $j){
                if($prefix.$j == $i){
                    $field_name = $j;
                    break;
                }
            }
            if(property_exists($data, $field_name)){
                $val = $data->$field_name;
                if(in_array($field_name, $user_fields))
                    $val = $this->getUserName($data->$field_name, $ClientHelper);
                $to_return[] = $val;
            }
        }
        return $to_return;
    }

    public function createAddressText($excl, $data, $field_name=''){
        foreach($excl as $i){
            if(property_exists($data, $i))
                unset($data->$i);
        }
        return [trim(implode((array)$data, " "))];
    }

    public function rearrangeClientProfileFields($headers, $data = null){
        $priority_order = [
            'ClientRefNum',
            'InsuredName',
            'FirstName',
            'PreferredName',
            'Surname',
            'HomeAddress',
            'MobilePhoneNumber',
            'EmailAddress',
            'AustralianBusinessNumber',
            'TradingName',
            'BusinessStructureType'
        ];
        $priority_order_len = count($priority_order);
        $priority_order_data = [];
        $headers_len = count($headers);
        foreach($headers as $k=>$i){
            foreach($priority_order as $h=>$j){
                if($i == $j){
                    $priority_order_data[$h] = $k;
                    unset($headers[$k]);
                }
            }
        }

        ksort($priority_order_data);
        $headers = array_combine(range($priority_order_len+1, $headers_len), array_values($headers));
        $headers = array_merge($priority_order, $headers);
        if(empty($data))
            return $headers;
        $temp_data = [];
        foreach($priority_order_data as $k => $i){
            $temp_data[] = $data[$i];
            unset($data[$i]);
        }
        $data = array_combine(range(count($priority_order_data)+1, $headers_len), array_values($data));
        $data = array_merge($temp_data, $data);
        return $data;
    }

    public function getAllClientProfiles(ClientHelper $ClientHelper){
        // from client changes 01/26/2017 - no client id, show all clients from all organisations.
        $client_headers = [];
        $client_headers_old = [];
        $exclusions_client = ['ClientID','InsurableBusinessID','CreatedDateTime','CreatedBy','ModifiedBy','ModifiedDateTime'];
        $exclusions = ['ClientID','ContactID', 'InsurableBusinessID','CompanyName','UserID','BusinessStructureTypeID','DisplayText','HomeAddressID','PostalAddressID','CreatedDateTime','CreatedBy','ModifiedBy','ModifiedDateTime'];
        $rename_fields = ['CreatedDateTime','CreatedBy','ModifiedBy','ModifiedDateTime'];
        $user_fields = ['CreatedBy','ModifiedBy'];
        $exclusions_address = ['AddressID','CreatedDateTime','CreatedBy','ModifiedBy','ModifiedDateTime'];
        $client_profiles = [];
        //get contacts by Organisation.
        $orgs = $ClientHelper->Organisation_GetOrganisations();
        $counter = 0;
        foreach($orgs as $k=>$i){
            $contacts = $ClientHelper->Oganisation_GetContacts($i->OrganisationID);
            foreach($contacts as $h){
                $contact = $ClientHelper->Contact_Get_first($h->ContactID);
                $client_ids = $ClientHelper->Contact_GetClientIDs($h->ContactID);
                foreach($client_ids as $j){
                    $client = $ClientHelper->Client_Get_first($j->ClientID);
                    $insurable_business = $ClientHelper->InsurableBusiness_Get_first($client->InsurableBusinessID);
                    if($counter == 0){
                        $client_headers = array_merge($client_headers, $this->stripOffExcludedFields($exclusions_client,$rename_fields,'Client',$client));
                        $client_headers = array_merge($client_headers, $this->stripOffExcludedFields($exclusions,$rename_fields,'Contact',$contact));
                        $client_headers = array_merge($client_headers, ['HomeAddress','PostalAddress']);
                        $client_headers = array_merge($client_headers, $this->stripOffExcludedFields($exclusions,$rename_fields,'InsurableBusiness',$insurable_business));
                        $client_headers = array_merge($client_headers, ['BusinessStructureType']);
                        $client_headers = array_unique($client_headers);
                        $client_headers_old = $client_headers;
                        $client_headers = $this->rearrangeClientProfileFields($client_headers);
                    }
                    $cid = $j->ClientID;
                    $client_profiles[$cid] = $this->getRecordsWithCustomHeaders($client_headers_old, 'Client', $rename_fields, $user_fields, $client,$ClientHelper);
                    $client_profiles[$cid] = array_merge($client_profiles[$cid], $this->getRecordsWithCustomHeaders($client_headers_old, 'Contact', $rename_fields, $user_fields, $contact,$ClientHelper));
                    if(!empty($contact->HomeAddressID))
                        $client_profiles[$cid] = array_merge($client_profiles[$cid], $this->createAddressText($exclusions_address,$ClientHelper->Address_Get_first($contact->HomeAddressID)));
                    else
                        $client_profiles[$cid][] = null;
                    if(!empty($contact->PostalAddressID))
                        $client_profiles[$cid] = array_merge($client_profiles[$cid], $this->createAddressText($exclusions_address,$ClientHelper->Address_Get_first($contact->PostalAddressID)));
                    else
                        $client_profiles[$cid][] = null;
                    $client_profiles[$cid] = array_merge($client_profiles[$cid], $this->getRecordsWithCustomHeaders($client_headers_old, 'InsurableBusiness', $rename_fields, $user_fields, $insurable_business,$ClientHelper));
                    if(!empty($insurable_business->BusinessStructureTypeID))
                        $client_profiles[$cid][] = $ClientHelper->BusinessStructureType_Get_first($insurable_business->BusinessStructureTypeID)->Name;
                    else
                        $client_profiles[$cid][] = null;
                    $client_profiles[$cid] = $this->rearrangeClientProfileFields($client_headers_old, $client_profiles[$cid]); // rearrange!
                    $counter++;
                }
            }
        }
        foreach($client_headers as $k=>$i){
            $client_headers[$k] = preg_replace('/(?<!\ )[A-Z]/', ' $0', $i);
        }
        return ['client_headers'=>$client_headers, 'client_profiles'=>$client_profiles];
    }

    public function profiles(ClientHelper $ClientHelper, $ClientID=null) {
        if ($ClientID && ($Client = Entity::get("Client", $ClientID, 1))) {
            /*
            $Client['Contacts'] = Entity::getMultiple('Contact', Entity::model()->Client_GetContactIDs($ClientID), 2);

            $Client['NumOfRecommends'] = count((array)$ClientHelper->getRecommendedPolicies($ClientID, true));
            $Client['NumOfPolicies'] = count((array)$ClientHelper->getCurrentPolicies($ClientID, true));

            app('request')->merge($Client);
            #return response()->json(app('request')->all());
            */
            $client_infos = $ClientHelper->Client_Get_first($ClientID);
            $client_contact = $ClientHelper->Client_GetContactIDs($ClientID);
            $client_contact = end($client_contact);
            $contact = $ClientHelper->Contact_Get_first($client_contact->ContactID);
            $insurable_business = $ClientHelper->InsurableBusiness_Get_first($client_infos->InsurableBusinessID);
            //$business_structure_type = $ClientHelper->BusinessStructureType_Get_first($insurable_business->BusinessStructureTypeID);
            $business_structure_types_raw = (array)$ClientHelper->BusinessStructureType_GetBusinessStructureTypes();
            $business_structure_types = [];
            foreach($business_structure_types_raw as $i){
                $business_structure_types[$i->BusinessStructureTypeID] = $i->DisplayText;
            }
            $mail_address_id = empty($contact->PostalAddressID)?$contact->HomeAddressID:$contact->PostalAddressID;
            if(!empty($mail_address_id))
                $mail_address = $ClientHelper->Address_Get_first($mail_address_id);
            $Client = [
                'InsuredName' => $client_infos->InsuredName,
                'ContactPersion' => $contact->FirstName.' '.$contact->Surname,
                'EmailAddress' => $contact->EmailAddress,
                'PhoneNumber' => $contact->MobilePhoneNumber,
                'InsurableBusinessID' => $client_infos->InsurableBusinessID,
                'TradingName' => $insurable_business->TradingName,
                'BusinessStructureTypes' => $business_structure_types,
                'BusinessStructure' => $insurable_business->BusinessStructureTypeID,//$business_structure_type->DisplayText,
                'ABN' => $insurable_business->AustralianBusinessNumber,
                'RegisterdForGST' => $insurable_business->IsRegisteredForGST,
                'AddressID' => $mail_address_id,
                //'UnitNumber' => $mail_address->UnitNumber,
                //'StreetNumber' => $mail_address->StreetNumber,
                //'StreetName' => $mail_address->StreetName,
                'AddressLine1' => $mail_address->Address1,
                'AddressLine2' => $mail_address->Address2,
                'City' => $mail_address->City,
                'County' => $mail_address->Country,
                'Postcode' => $mail_address->Postcode,
                'State' => $mail_address->State,
                'Contacts' => [Entity::get('Contact', Entity::model()->Client_GetContactIDs_first($ClientID), 2)]
            ];
            return view("Client.Profiles.list", compact('Client', 'ClientID'));
        }
        $all_profiles = $this->getAllClientProfiles($ClientHelper);
        $client_headers = $all_profiles['client_headers'];
        $client_profiles = $all_profiles['client_profiles'];
        return view("Client.Profiles.list", compact('Client', 'ClientID', 'client_headers','client_profiles'));
    }

    public function renderAllProfiles($client_headers, $client_profiles){
        $rendered = '';
        foreach($client_profiles as $k=>$i){
            $rendered .= '<tr cid="'.$k.'">';
            foreach($i as $h=>$j){
                $rendered .= '<td>'.($h<5?'<a href="'.route('client.profiles',[$k]).'">'.$j.'</a>':$j).'</td>';
            }
            $rendered .= '</tr>';
        }
        return $rendered;
    }

    public function allProfilesGetRendered(ClientHelper $ClientHelper){
        $all_profiles = $this->getAllClientProfiles($ClientHelper);
        $client_headers = $all_profiles['client_headers'];
        $client_profiles = $all_profiles['client_profiles'];
        return $this->renderAllProfiles($client_headers, $client_profiles);
    }

    public function getData(ClientHelper $ClientHelper, $ClientID, $fields="") {
        $client = Entity::get('Client', $ClientID, 2, Entity::getReturnTypeObject());

        if (!$client) 
            return response()->json(Entity::model()->getLastSpError());
        if (!$fields) 
            return response()->json($client);

        $fields = explode(",", $fields);

        foreach($fields as $field) {
            if ($field === 'recommendations') {
                $client->recommendations = $ClientHelper->getRecommendations($ClientID, false);
            }
            elseif($field === 'current_policies') {
                $client->current_policies = $ClientHelper->getCurrentPolicies($ClientID, false);
            }
            elseif($field === 'tasks') {
                $client->tasks = $ClientHelper->getOpenTasks($ClientID, false);
            }
            elseif($field === 'contacts') {
                $client->contacts = $ClientHelper->getContacts($ClientID, false);
            }
        }

        return response()->json($client);
    }

    public function contactUpdate(Request $request, $ClientID, $ContactID) {
        $Contact = Entity::get('Contact', $ContactID, 1, Entity::getReturnTypeObject());

        if (!$Contact) {
            return response()->json(Entity::model()->getLastSpError());
        }

        $validator = Validator::make($request->all(), [
            'FirstName'         => 'required',
            'PreferredName'     => 'required',
            // 'MiddleNames'    => 'required',
            'Surname'           => 'required',
            'EmailAddress'      => 'required|email',
            'MobilePhoneNumber' => 'required',
            'BirthDate'         => 'nullable|date_format:Y-m-d'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->getMessageBag()->toArray());
        }

        $contact = [
            /*'ContactID'           =>*/ $ContactID,
            /*'FirstName'           =>*/ $request->FirstName,
            /*'MiddleNames'         =>*/ $request->MiddleNames ?: $Contact->MiddleNames,
            /*'Surname'            =>*/  $request->Surname,
            /*'PreferredName'       =>*/ $request->PreferredName,
            /*'EmailAddress'        =>*/ $request->EmailAddress,
            /*'MobilePhoneNumber'   =>*/ $request->MobilePhoneNumber,
            /*'BirthDate'           =>*/ $Contact->BirthDate,
            /*'BirthCity'           =>*/ $Contact->BirthCity,
            /*'BirthCountry'        =>*/ $Contact->BirthCountry,
            /*'CurrentUserID'       =>*/ Auth::check() ? Auth::id() : null
        ];

        if (Entity::model()->Contact_Update_first($contact)) {
            return response()->json(['success' => true]);
        }
        return response()->json(Entity::model()->getLastSpError());
    }

    public function addressUpdate(Request $request, $ClientID, $AddressID) {
        $Address = Entity::get('Address', $AddressID, 1, Entity::getReturnTypeObject());
        
        if (!$Address) {
            return response()->json(Entity::model()->getLastSpError());
        }

        $validator = Validator::make($request->all(), [
            //'UnitNumber'   => 'numeric',
            //'StreetNumber' => 'numeric',
            //'StreetName'   => 'required',
            'AddressLine1'  => 'required',
            'City'         => 'required',
            'State'        => 'required',
            'City'         => 'required',
            'Postcode'     => 'required',
            'Country'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->getMessageBag()->toArray());
        }

        $address = [
            /*'AddressID'     =>*/ $AddressID,
            /*'UnitNumber'    =>*/ //$request->UnitNumber,
            /*'StreetNumber'  =>*/ //$request->StreetNumber,
            /*'StreetName'    =>*/ //$request->StreetName,
            /*'AddressLine1'  =>*/ $request->AddressLine1,
            /*'AddressLine2'  =>*/ $request->AddressLine2,
            /*'City'          =>*/ $request->City,
            /*'State'         =>*/ $request->State,
            /*'Postcode'      =>*/ $request->Postcode,
            /*'Country'       =>*/ $request->Country,
            /*'CurrentUserID' =>*/ Auth::check() ? Auth::id() : null
        ];

        if (Entity::model()->Address_Update_first($address)) {
            return response()->json(['success' => true]);
        }
        return response()->json(Entity::model()->getLastSpError());
    }

    public function recommendations(ClientHelper $ClientHelper, $ClientID) {
        $client = Entity::get('Client', $ClientID, 1, Entity::getReturnTypeObject());
        
        if (!$client)
            abort(404);

        $client->Business = Entity::get('InsurableBusiness', $client->InsurableBusinessID);
        $client->Contact = Entity::get('Contact', Entity::model()->Client_GetContactIDs_first($ClientID), 2);

        return view("Client.Recommendations.view", compact('client', 'ClientID'));
    }

    public function addRecommendation(Request $request, $ClientID) {

        if ($request->ClientRecommendationID) {
            Entity::model()->ClientRecommendation_Delete_first($request->ClientRecommendationID);
        }

        $notes = $request->Notes ? str_limit($request->Notes, 1000) : null;
        $data = [
            /*'ClientID'      =>*/ $ClientID,
            /*'PolicyTypeID'  =>*/ $request->PolicyTypeID,
            /*'Notes'         =>*/ $notes,
            /*'CurrentUserID' =>*/ Auth::check() ? Auth::id() : null
        ];

        if ($result = Entity::model()->ClientRecommendation_Create_first($data, ['ClientRecommendationID' => 'uniqueidentifier'])) {
            return response()->json([
                'ClientID'                  => $ClientID,
                'PolicyTypeID'              => $request->PolicyTypeID,
                'Notes'                     => [
                    'summary'   => str_limit($notes, 10),
                    'full'      => $notes
                ],
                'ClientRecommendationID'    => $result->ClientRecommendationID,
                'CreatedBy'                 => Auth::check() ? Auth::id() : null,
                'CreatedDateTime'           => Carbon::now()->format('m/d/y'),
                'ModifiedBy'                => null,
                'ModifiedDateTime'          => null
            ]);
        }
        else {
            return response()->json(Entity::model()->getLastSpError());
        }
    }

    public function removeRecommendation(Request $request, $ClientID) {
        $data = [
            /*'ClientRecommendationID'  =>*/ $request->ClientRecommendationID,
        ];

        if ($result = Entity::model()->ClientRecommendation_Delete_first($data)) {
            return response()->json(["success" => true]);
        }
        else {
            return response()->json(Entity::model()->getLastSpError());
        }
    }

    public function quotes(Request $request, $ClientID=null) {
    	$client  = Entity::get('Client', $ClientID, 1, Entity::getReturnTypeObject());
    	$quotes  = []; //Entity::getMultiple('InsuranceQuote', Entity::model()->Client_GetQuoteIDs($ClientID), 2);
    	$current = $expired = [];
      
    	foreach($quotes as $quote) {
    		$quote['Notes'] = Entity::getMultiple('Note', Entity::model()->Note_GetNotesByParentID($quote['InsuranceQuoteID']));
    		$quote['Client'] = (array)$client;

    		if (!empty($quote['Notes'])) {
    			foreach($quote['Notes'] as &$Note) 
                    if (!empty($Note['CreatedBy']))
    	                   $Note['User'] = Entity::get('User', $Note['CreatedBy']);
    		}

    		$quote['ExpiryDateTime'] = $quote['ExpiryDateTime'] ? Carbon::parse($quote['ExpiryDateTime']) : null;
    		$quote['CreatedDateTime'] = $quote['CreatedDateTime'] ? Carbon::parse($quote['CreatedDateTime']) : null;
    		$quote['CoverStartDateTime'] = $quote['CoverStartDateTime'] ? Carbon::parse($quote['CoverStartDateTime']) : null;
    		$quote['CoverEndDateTime'] 	= $quote['CoverEndDateTime'] ? Carbon::parse($quote['CoverEndDateTime']) : null;

    		$quote['status'] = !($quote['ExpiryDateTime'] && $quote['ExpiryDateTime']->diff(Carbon::now())->s);

    		if ($quote['status'])	
    			$current[] = (array)$quote;
    		else $expired[] = (array)$quote;
    	}
        
    	return view('Client.Quotes.list', compact('current', 'expired', 'client', 'ClientID'));
    }

    public function history($ClientID) {
        $client = Entity::get('Client', $ClientID, null, Entity::getReturnTypeObject());
        $rfqs = Entity::getMultiple('RFQ', Entity::model()->Client_GetRFQIDs($ClientID), 2);

        return view("Client.History.list", compact('client', 'rfqs'));
    }

    public function expireQuote($ClientID, $InsuranceQuoteID) {
    	$Quote = Entity::get('InsuranceQuote', $InsuranceQuoteID, 1, Entity::getReturnTypeObject());
    	
    	if ($Quote) {
    		$saved = Entity::model()->InsuranceQuote_Update_first([
	    		/*'InsuranceQuoteID'  	=>*/ $Quote->InsuranceQuoteID,
	    		/*'UnderwriterID'     	=>*/ $Quote->UnderwriterID,
	    		/*'RFQID'				=>*/ $Quote->RFQID,
	    		/*'CoverStartDateTime'	=>*/ $Quote->CoverStartDateTime,
	    		/*'CoverEndDateTime'	=>*/ $Quote->CoverEndDateTime,
	    		/*'EffectiveDateTime'	=>*/ $Quote->EffectiveDateTime,
	    		/*'ExpiryDateTime'		=>*/ Carbon::now()->format('m/d/Y H:i:s'),
	    		/*'InsurancePolicyID'	=>*/ $Quote->InsurancePolicyID,
	    		/*'FinalizeDateTime'	=>*/ $Quote->FinalizedDateTime,
	    		/*'AddressID'			=>*/ $Quote->AddressID,
	    		/*'Classification'		=>*/ $Quote->Classification,
	    		/*'Product'				=>*/ $Quote->Product,
	    		/*'Premium'				=>*/ $Quote->Premium,
	    		/*'CurrentUserID'		=>*/ Auth::check() ? Auth::id() : null
	    	]);

	    	return response()->json($saved ? ['success' => true] : Entity::model()->getLastSpError());
    	}
    	return response()->json(Entity::model()->getLastSpError());
    }

    public function finalizeQuote($ClientID, $InsuranceQuoteID) {
        $Quote = Entity::get('InsuranceQuote', $InsuranceQuoteID, 1, Entity::getReturnTypeObject());
        
        if ($Quote) {
            $saved = Entity::model()->InsuranceQuote_Finalize_first([
                /*'InsuranceQuoteID'    =>*/ $Quote->InsuranceQuoteID,
                /*'CurrentUserID'       =>*/ Auth::check() ? Auth::id() : null
            ], ['InsurancePolicyID' => 'uniqueidentifier']);

            return response()->json($saved ? ['success' => true] + (array)$saved : Entity::model()->getLastSpError());
        }
        return response()->json(Entity::model()->getLastSpError());
    }

    public function shareQuote($ClientID, $InsuranceQuoteID) {
        $Quote = Entity::get('InsuranceQuote', $InsuranceQuoteID, 1, Entity::getReturnTypeObject());
        
        if ($Quote) {
            $saved = true;

            /**
             * @todo Share Quote to other Clients
             */
            return response()->json($saved ? ['success' => true] : Entity::model()->getLastSpError());
        }
        return response()->json(Entity::model()->getLastSpError());
    }

    public function scanUploadedQuotes(Request $request, $ClientID) {
        $file = $request->file('file');

        $validator = Validator::make([
            'file' => $file,
            'extension' => strtolower($file->getClientOriginalExtension()),
        ], [
            'file' => 'required',
            'extension' => 'required|in:csv'
        ]);

        if ($validator->fails()) {
            response()->json(['error' => $validator->getMessageBag()->toArray()]);
        }

        if ($handle = @fopen($file->getPathname(), 'r')) {
            $result = ['body' => []];
            while($data = fgetcsv($handle, 1000, ',')) {
                if (empty($result['headers'])) {
                    $result['headers'] = $data;
                }
                else {
                    $row = [];
                    foreach($result['headers'] as $key => $header) {
                        $row[$header] = $data[$key];    
                    }
                    $result['body'][] = $row;    
                } 
            }
            if (!empty($result['body']))
                return response()->json($result);
        }

        return response()->json(['error' => 'Either the file is not readable or empty.']);
    }

    public function saveUploadedQuotes(Request $request, $ClientID) {
        
    }
    /* orly */
    public function clientCurrentPolicies(Request $request, ClientHelper $ClientHelper, $ClientID, TasksController $tc, NotesController $nc, FileAttachmentsController $fc){

        if ($ClientID && ($Client = Entity::get("Client", $ClientID, 3))) {
        
            $client_infos = $ClientHelper->Client_Get_first($ClientID);
            $client_contact = $ClientHelper->Client_GetContactIDs($ClientID);
            $client_contact = end($client_contact);
            $contact = $ClientHelper->Contact_Get_first($client_contact->ContactID);

            $Client = [
                'InsuredName' => $client_infos->InsuredName,
                'ContactPersion' => $contact->FirstName.' '.$contact->Surname,

            ];

            //list current policies 22075ACC-16E8-46FC-B0B8-2A206E2FD46F
            $get_client_policies_id = $ClientHelper->Client_GetPolicyIDs($ClientID);
            $client_policies = array();
            // dd($get_client_policies_id);
             // dd(Entity::get('Note', '56C13917-9591-40AF-9125-7D43B9737050'));
            foreach($get_client_policies_id as $a){
                    $policy_details[] = $this->getPolicyDetails($request, $ClientHelper, $a->InsurancePolicyID);

                    // dd($policy_details);
            }   
            $EntityName = 'InsurancePolicy';

            $ParentID = null;//'94C5A804-E760-4C81-B9BC-4F43EC0C83BF';

            $TaskTypeID = null;
            list($task_content, $task_buttons, $css, $js) = $tc->createTaskInterface($EntityName, $ParentID, $ClientID, null, null, true, false, true, true, false);

            list($note_content, $note_buttons, $css_notes, $js_notes) = $nc->notesInterface(
                $EntityName,
                $ParentID,
                true
            );
            list($attachment_content, $attachment_buttons, $css_attachments, $js_attachments) = $fc->attachmentsInterface(
                $EntityName,
                $ParentID,
                true,
                false,
                true
            );

            $css = array_merge($css, $css_notes, $css_attachments);
            $js = array_merge($js, $js_notes, $js_attachments);
            //$note_content = $nc->noteInterface($ParentID, $canAddNote = false);
             // return view('uis.Claims.Admin.index', compact('task_content', 'EntityName', 'ParentID', 'TaskTypeID', 'css', 'js'));
            // dd($js);
            return view("Client.Policies.index", compact('Client', 'ClientID', 'policy_details', 'task_content', 'task_buttons', 'EntityName', 'ParentID', 'TaskTypeID', 'css', 'js','note_content', 'attachment_content'));

        }

    }

    public function getPolicyDetails(Request $request, ClientHelper $ClientHelper, $policy_id){
        $details = array(
            'details' => Entity::get("InsurancePolicy", $policy_id, 3), 
            'notes' => $this->getNote($request, $ClientHelper, $policy_id), 
            'tasks' => $this->getTask($request, $ClientHelper, $policy_id),
            'attachments' => $this->getAttachments($request, $ClientHelper, $policy_id)
            );

        return $details;

    }

    public function getNote(Request $request, ClientHelper $ClientHelper, $parent_id){
        $notes_id = $ClientHelper->Note_GetNotesByParentID($parent_id);
        // $notes = Entity::get("Note", '94C5A804-E760-4C81-B9BC-4F43EC0C83BF', 3);
        // dd($notes_id)
        $insurance_notes = [];

        foreach( $notes_id as $key => $value ){
            $insurance_notes[$value->NoteID] = $ClientHelper->Note_Get($value->NoteID);

        }

        return $insurance_notes;

    }

    public function getTask(Request $request, ClientHelper $ClientHelper, $parent_id){
        $task_ids = $ClientHelper->Task_GetIDsByParentID($parent_id);
        $insurance_tasks = [];

            foreach( $task_ids as $key => $value ){
                $insurance_tasks[ $value->TaskID ] = $ClientHelper->Task_Get($value->TaskID);

            }

        return $insurance_tasks;

    }

    public function getAttachments(Request $request, ClientHelper $ClientHelper, $parent_id){
        $attacments_id = $ClientHelper->FileAttachment_GetFileAttachmentsByParentID($parent_id);
        $insurance_attachments = [];

            foreach( $attacments_id as $key => $value ){
                $insurance_attachments[ $value->FileAttachmentID ] = $ClientHelper->FileAttachment_Get($value->FileAttachmentID);

            }

        return $insurance_attachments;

    }



}
