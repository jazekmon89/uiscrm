<?php

namespace App\Helpers;

use App\Search;
use App\Providers\Facades\Entity;
use Illuminate\Http\Request;

class SearchHelper {

	protected $search = null;

	public function __construct(Search $search, Request $request) {
		$this->search = $search;
        $this->request = $request;
	}

    public function model() {
        return $this->search;
    }

	public function search($method, $params=[]) {
        $params = $params ?: $this->search->getSearchParams($this->request, $method);
        
        return call_user_func_array([$this, $method], [$params]);
    }

    protected function FindInsuranceEntitiesByInsuranceDetails($params) {
        $entities = (array)$this->search->FindInsuranceEntitiesByInsuranceDetails($params);
        foreach($entities as $key => $entity) { 
            $entity = Entity::getFromEntityTypeID($entity->EntityTypeID, $entity->EntityID, 2);

            if (!$entity) {
                unset($entities[$key]);
            }
            else $entities[$key] = $entity;
        }

        return $entities;
    }


    protected function Address_Find($params) {
        return Entity::getMultiple('Address', $this->search->Address_Find($params));
    }

    /**
    * @param key AddressID supply array for multiple IDs or look for request Address Input
    * @return Contact Objects with Addresses
    * @todo linke between Contact And Client
    */
    protected function FindContactByPersonalDetails($params) {
        $address_params = $this->search->getSearchParams($this->request, 'Address_Find', 'Address.');
        $addresses      = $address_params ? (array)$this->search->Address_Find($address_params) 
                                            : (array)array_pull($params, "AddressID");
        if ($addresses) {
            $contacts = [];
            foreach($addresses as $address) {

                // search per address found
                array_set($params, "7", array_get((array)$address, "AddressID"));    

                /** 
                 * merge all results
                 * @todo filter repeated contact if occur
                 */
                $contacts = array_merge($contacts, (array)$this->search->FindContactByPersonalDetails($params));
            }
            
        }
        else {
            $params   = $this->search->getSearchParams($this->request, 'FindContactByPersonalDetails');
            $contacts = (array)$this->search->FindContactByPersonalDetails($params);
        }
        $includes = $this->request->includes ?: [];
        $contacts = Entity::getMultiple('Contact', $contacts, 2);
        if (in_array('clients', $includes)) {
            foreach($contacts as $key => $contact) {     
                $contacts[$key]['Clients'] = Entity::getMultiple('Client', $this->search->Contact_GetClientIDs($contact['ContactID']));
                foreach($contacts[$key]['Clients'] as $k=>$i){
                    if(!empty($i['CreatedBy']))
                        $contacts[$key]['Clients'][$k]['CreatedBy'] = $this->search->Contact_GetUserByID_first($i['CreatedBy']);
                    if(!empty($i['ModifiedBy']))
                        $contacts[$key]['Clients'][$k]['ModifiedBy'] = $this->search->Contact_GetUserByID_first($i['ModifiedBy']);
                }
            }
        }

        return $contacts;
    }

    protected function FindClientByClientAndBusinessDetails($params) {
        $clients = Entity::getMultiple('Client', $this->search->FindClientByClientAndBusinessDetails($params), 3);
        $includes = $this->request->includes ?: [];
        
        if (in_array('contacts', $includes)) {
            foreach($clients as $key => $client) {    
                $clients[$key]['Contacts'] = Entity::getMultiple('Contact', $this->search->Client_GetContactIDs($client['ClientID']), 2);

                foreach($clients[$key]['Contacts'] as $k=>$i){
                    if(!empty($i['CreatedBy']))
                        $clients[$key]['Contacts'][$k]['CreatedBy'] = $this->search->Contact_GetUserByID_first($i['CreatedBy']);
                    if(!empty($i['ModifiedBy']))
                        $clients[$key]['Contacts'][$k]['ModifiedBy'] = $this->search->Contact_GetUserByID_first($i['ModifiedBy']);
                }
            }
        }

        return $clients;
    }

    protected function FindRFQByRFQContactLeadAndBusinessDetails($params) {
        return $this->search->FindRFQByRFQContactLeadAndBusinessDetails($params);
    } 

    public function __call($method, $params) {
    	return call_user_func_array([$this->search, $method], $params);
    }

    protected function FindInsuranceQuoteByInsuranceQuoteDetails($params) {
        return $this->search->FindInsuranceQuoteByInsuranceQuoteDetails($params);
    }
}
