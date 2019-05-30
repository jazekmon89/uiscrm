<?php

namespace App\Helpers;

use App\Providers\Facades\Entity;
use App\Helpers\ThemeHelper;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class OrganisationHelper 
{

	protected static $cache_key = "Orgs";

	protected static $cache_minutes = 1440;

	public function __construct() 
	{

	}

	public static function getOrganisationIDByName($OrgName)
	{
		$orgs = self::getAll();
		$uis_id = null;
		foreach($orgs as $k=>$i){
			if($i->Name == $OrgName)
				return $i->OrganisationID;
			else if($i->Name == 'Ultra Insurance Solutions')
				$uis_id = $i->OrganisationID;
		}
		return $uis_id;
	}

	public static function getDefaultOrganisation() 
	{
		/**
		 * @var JCI Organisation ID
		 */
		//return 'DE09F4B6-C708-4F5F-A48E-432AF31E4D74';
		if ($settings = ThemeHelper::getSettings(ThemeHelper::getDomain()))
		{
			if (array_has($settings, 'OrganisationID'))
			{
				return array_get($settings, 'OrganisationID');
			}
		}
		return self::getOrganisationIDByName('Just Coffee Insurance');
	}

	public static function getCurrentOrganisationAbbrv()
	{
		if(strpos($_SERVER['HTTP_HOST'], 'justcoffeeinsurance') !== false)
			return 'jci';
		else
			return 'uis';
	}

	public static function getCurrentOrganisationName()
	{
		$abbrv = self::getCurrentOrganisationAbbrv();
		if($abbrv == 'jci')
			return 'Just Coffee Insurance';
		else
			return 'Ultra Insurance Solutions';
	}

	public static function getCurrentOrganisationID()
	{
		$orgs = self::getAll();
		return self::getOrganisationIDByName(self::getCurrentORganisationName());
	}

	public static function getPolicyTypes($OrganisationID=null, $id_keys=false)
	{
		$Policies = Cache::remember("OrganisationPolicies.{$OrganisationID}", 2800, function() use ($OrganisationID) {
			return Entity::model()->Organisation_GetPolicyTypes($OrganisationID ?: static::getDefaultOrganisation());
		});

		if ($id_keys) 
		{
            foreach($Policies as $key => $policy) 
            {
                $Policies[$policy->PolicyTypeID] = $policy;
                unset($Policies[$key]);
            }
        }

        return $Policies;
	}

	public static function getPolicyIDs($OrganisationID=null)
	{
		$Policies = Cache::remember("OrganisationPolicyIDs.{$OrganisationID}", 2800, function() use ($OrganisationID) {
			return Entity::model()->Organisation_GetPolicyIDs($OrganisationID ?: static::getDefaultOrganisation());
		});

        return $Policies;
	}


	public static function getAll() 
	{
		return Cache::remember(static::$cache_key, static::$cache_minutes, function() 
		{
			return Entity::model()->Organisation_GetOrganisations();
		});
	}

	public static function countAllQuotes($OrganisationID=null)
	{	
		if (($global_counter = static::globalCounter($OrganisationID))
		&& array_has($global_counter, "QuoteCount"))
			return array_get($global_counter, 'QuoteCount');

		return count(Entity::model()->Organisation_GetCurrentQuoteIDs($OrganisationID ?: static::getDefaultOrganisation()));
	}

	public static function countAllClients($OrganisationID=null)
	{	
		if (($global_counter = static::globalCounter($OrganisationID))
		&& array_has($global_counter, "ClientCount"))
			return array_get($global_counter, 'ClientCount');

		$Contacts = Entity::model()->Oganisation_GetContacts($OrganisationID ?: static::getDefaultOrganisation());

		if (!$Contacts) return 0;

		$count = 0;
		foreach($Contacts as $Contact) 
		{
			$count += count(Entity::model()->Contact_GetClientIDs($Contact->ContactID));
		}

		return $count;	
	}

	public static function countAllRFQs($OrganisationID=null)
	{
		if (($global_counter = static::globalCounter($OrganisationID))
		&& array_has($global_counter, "RFQCount"))
			return array_get($global_counter, 'RFQCount');

		return count(Entity::model()->Organisation_GetRFQIDs($OrganisationID ?: static::getDefaultOrganisation()));
	}

	public static function countAllPolicies($OrganisationID=null)
	{
		if (($global_counter = static::globalCounter($OrganisationID))
		&& array_has($global_counter, "PolicyCount"))
			return array_get($global_counter, 'PolicyCount');

		return count(Entity::model()->Organisation_GetCurrentPolicyIDs($OrganisationID ?: static::getDefaultOrganisation()));
	}

	public static function countAllClaims($OrganisationID=null)
	{
		if (($global_counter = static::globalCounter($OrganisationID))
		&& array_has($global_counter, "ClaimCount"))
			return array_get($global_counter, 'ClaimCount');

		return count(Entity::model()->Organisation_GetCurrentClaimIDs($OrganisationID ?: static::getDefaultOrganisation()));
	}

	public static function countAllTasks($OrganisationID=null)
	{	
		if (($global_counter = static::globalCounter($OrganisationID))
		&& array_has($global_counter, "TaskCount"))
			return array_get($global_counter, 'TaskCount');

		return count(Entity::model()->Organisation_GetOpenTaskIDs($OrganisationID ?: static::getDefaultOrganisation()));
	}

	public static function globalCounter($OrganisationID=null)
	{
		return (array)Entity::model()->DashboardOrganisationSummary_first($OrganisationID ?: static::getDefaultOrganisation());
	}

	public function __call($method, $params) 
	{
    	return call_user_func_array([Entity::model(), $method], $params);
    }
}