<?php

namespace App\Helpers;

use App\Helpers\UserHelper;
use App\Providers\Facades\Entity;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientHelper {
    // 'B5E1B107-FF1E-4FA4-9A76-D2273C61843F'
    // '22075ACC-16E8-46FC-B0B8-2A206E2FD46F'
    protected $testID = null;//'5D4D5C21-F7D2-E611-8144-02A5618F3995';

	protected $client = null;

	public function __construct(Request $request) {
        $this->request = $request;
	}

    public function testID() {
        return $this->testID;
    }

    public function model() {
        return $this->client;
    }

    public function getUserAccount($ClientID=null) {
        $ClientID = $ClientID ?: $this->testID; 

        return (array)Entity::model()->GetClientUserByUserID($ClientID);
    }

    public function getClientID(){
        $contact = Entity::model()->Contact_GetByUserID_first(Auth::user()->user_id);
        if(!$contact){
            return null;
        }
        $client_id = Entity::model()->Contact_GetClientIDs($contact->ContactID);
        if($client_id)
            $client_id = current($client_id)->ClientID;
        if(!$client_id)
            return null;
        return $client_id;
    }


    public function getOpenTasks($ClientID=null, $ids_only=true) {
        $ClientID = $ClientID ?: $this->testID;
        $tasks = Entity::getMultiple('Task', Entity::model()->Task_GetIDsByParentID($ClientID));

        if ($ids_only) {
            return arr_lget($tasks, "TaskID");
        }

        foreach($tasks as $key => $task) {   

            if (!empty($task['AssignToOrganisationRoleID']) && $role = arr_lfind(UserHelper::getRoles(), "OrganisationRoleID", $task['AssignToOrganisationRoleID']))
                $task['Role'] = $role;

            if (!empty($task['AssignToUserID'])) 
                $task['Contact'] = Entity::get('UserContact', $task['AssignToUserID']);

            if (!empty($task['TaskStatusID'])) 
                $task['Status'] = Entity::get('TaskStatus', $task['TaskStatusID']);

            $tasks[$key] = $task;
            
        }

        return $tasks;
    }

    public function getCurrentPolicies($ClientID=null, $ids_only=true) {
        $ClientID = $ClientID ?: $this->testID;
        if(!$ClientID)
            return null;
        $policies = null;
        try{
            $policies = Entity::getMultiple('InsurancePolicy', Entity::model()->Client_GetCurrentPolicyIDs($ClientID), $ids_only ? 1 : 2);
        }catch(Exception $e){
            return $policies;
        }
        
        if ($ids_only) {
            return arr_lget($policies, 'InsurancePolicyID');
        }

        return $policies;
    }

    public function getContacts($ClientID=null, $ids_only=true, $include_address=false) {
        $ClientID = $ClientID ?: $this->testID;
        if(!$ClientID)
            return null;
        $contacts = Entity::getMultiple('Contact', Entity::model()->Client_GetContactIDs($ClientID), $include_address ? 2 : 1);

        if ($ids_only) {
            return arr_lget($contacts, 'ContactID');
        }

        /**
        * @todo get Address fields
        */
        #foreach($contacts as $contact) {}
        return $contacts;
    }

    public function getQuoteRequests($ClientID=null, $ids_only=true) {
        $ClientID = $ClientID ?: $this->testID;
        $contacts = $this->getContacts($ClientID);
        $requests = [];

        foreach($contacts as $ContactID) {
            $requests = array_merge($requests, Entity::getMultiple('RFQ', Entity::model()->Contact_GetRFQIDs($ContactID)), 2);
        }

        if ($ids_only) {
            return arr_lget($requests, 'RFQID');
        }

        return $requests;
    }

    public function getQuotes($ClientID=null, $ids_only=true) {
        $ClientID = $ClientID ?: $this->testID;
        $quotes = Entity::getMultiple('InsuranceQuote', Entity::model()->Client_GetQuoteIDs($ClientID), 2);

        if ($ids_only) {
            return arr_lget($quotes, 'InsuranceQuoteID');
        }
        return $quotes;
    }

    public function getRecommendations($ClientID, $ids_only=true) {
        $ClientID = $ClientID ?: $this->testID;

        $recommendations = Entity::getMultiple('PolicyType', Entity::model()->Client_GetRecommendations($ClientID));
        
        if ($ids_only) {
            return arr_lget($recommendations, 'PolicyTypeID');
        }

        return $recommendations;
    }

    public function getRecommendedPolicies($ClientID=null, $ids_only=true) {
        $ClientID = $ClientID ?: $this->testID;
        $recommendations = (array)Entity::model()->ClientRecommendation_Get($ClientID);

        if ($ids_only) {
            return arr_lget($recommendations, 'PolicyTypeID');
        }

        foreach($recommendations as $key => $recommendation) {
            if ($PolicyType = Entity::get('PolicyType', $recommendation->PolicyTypeID)) {
                $recommendation->PolicyType = $PolicyType;
            }
            else {
                unset($recommendations[$key]);
            }
            $recommendations[$key] = (array)$recommendation;
        }      
        return $recommendations;
    }

    public function __call($method, $params) {
    	return call_user_func_array([Entity::model(), $method], $params);
    }
}
