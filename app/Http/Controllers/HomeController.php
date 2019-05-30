<?php

namespace App\Http\Controllers;

use App\Helpers\ClientHelper;
use App\Providers\Facades\Entity;
use App\Helpers\OrganisationHelper as Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct(ClientHelper $ClientHelper) {
        $this->middleware('auth');
        $this->Client = $ClientHelper;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $ClientID=null)
    {
    	$adviser = Auth::check() && Auth::user()->is_adviser ? '-adviser' : '';

        if (!$adviser) {
            $Client = $policies = $user_policies = $requests = [];
            $clients = Auth::user()->clients;
            
            if (!$ClientID || (!$Client = arr_lfind($clients, "ClientID", $ClientID))) {
                $Client = head($clients);
            }
            
            if (!$Client) {
                $Client = Entity::get('Client', $this->Client->testID());
                $clients[] = $Client;
            }

            if ($Client) {
                $policies = $this->Client->getCurrentPolicies($Client['ClientID'], false);
                
                foreach($policies as $key => $policy) {
                    $PolicyType = array_get($policy, 'PolicyType');

                    if ($PolicyType && !isset($user_policies[$PolicyType['PolicyTypeID']])) {
                        $user_policies[$PolicyType['PolicyTypeID']] = $PolicyType;
                    }
                    $policies[$key] = $policy;
                }
            }
        }
        return view("home{$adviser}", compact('policies', 'requests', 'clients', 'Client', 'user_policies'));
    }

    public function generalInsurance($OrganisationID='all') 
    {

        $Organisations = Organisation::getAll();
        $OrganisationID = $OrganisationID ?: Organisation::getDefaultOrganisation();

        if ($OrganisationID === 'all') {
            $Organisation = $Organisations;
        }
        else {
            if (!$Organisation = arr_lfind($Organisations, 'OrganisationID', $OrganisationID))
            {
                $Organisation = head($Organisations);
            }
            $Organisation = [$Organisation];
        }

        $counter = (object)[
            'Client' => 0,
            'RFQ' => 0,
            'Quote' => 0,
            'Policy' => 0,
            'Claim' => 0,
            'Task' => 0
        ];

        foreach($Organisation as $Org)
        {
            if ($_counter = Organisation::globalCounter($Org->OrganisationID))
            {
                foreach($counter as $key => $value)
                {
                    $counter->$key += array_get($_counter, $key . 'Count', 0);
                }
            }
        }

        return view("general-insurance", compact('counter', 'Organisations', 'OrganisationID'));
    }
}
