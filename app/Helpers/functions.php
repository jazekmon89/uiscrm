<?php

if (!function_exists('pre')) 
{
	function pre($var, $die=false) 
	{
		print "<pre>" . print_r($var, 1) . "</pre>";
		if ($die) 
			die();
	}
}

if (!function_exists('convertBinToMSSQLGuid')) 
{
	function convertBinToMSSQLGuid($binguid)
	{
	        $unpacked = unpack('Va/v2b/n2c/Nd', $binguid);
	        return sprintf(
	        	'%08X-%04X-%04X-%04X-%04X%08X', 
	        	$unpacked['a'],  //8chars
	        	$unpacked['b1'], //4 
	        	$unpacked['b2'], //4  
	        	$unpacked['c1'], //4
	        	$unpacked['c2'], //4
	        	$unpacked['d']   //8
	        );
	}
}

/**
* Check if a guid is a valid msguid
* @see convertBinToMSSQLGuid
* @return <Bool>
*/
if (!function_exists('is_guid')) 
{
	function is_guid($uuid) 
	{
		return preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $uuid);
	}
}

function row_guid($guid, $row)
{
	return substr($guid, 0, strlen($guid) - 12) . str_pad($row, 12, '0', STR_PAD_LEFT);
}

function guid_row($guid)
{
	return (int)substr($guid, -12, 12);
}


use App\Providers\Facades\Entity;
use App\Helpers\ThemeHelper;
use Carbon\Carbon;

function business_structures($list=true)
{
	$structures = (array)Cache::remember("BusinessStructures", 2800, function() {
		return Entity::model()->BusinessStructureType_GetBusinessStructureTypes();
	});

	if ($list) return arr_pairs($structures, "BusinessStructureTypeID", "DisplayText");
	return $structures;	
}

function all_states($country_name="Australia", $fresh=false) 
{
	static $states = [];

	$country_name = strtolower($country_name);

	if (!$fresh && isset($states[$country_name]))
	{
		return $states[$country_name];
	}

	return $states[$country_name] = arr_pairs(
		Entity::model()->State_GetStatesByCountryName($country_name), 
		'StateShortName', 
		'StateLongName'
	);
}

function state_long_name($stateShortName, $country_name="")
{
	return array_get((array)all_states($country_name), $stateShortName, $stateShortName);
}	

/**
* Global function to get current theme
*/
function theme() 
{
	return ThemeHelper::getTheme();
}

function address($address) 
{
	$addr = array_only((array)$address, [
		//'UnitNumber', 
		//'StreetNumber', 
		//'StreetName', 
		'AddressLine1',
		'AddressLine2',
		'State',
		'Country', 
		'Postcode'
	]);

	if ($addr) 
	{
		// make sure we display the state full name
		$addr['State'] = state_long_name($addr['State'], $addr['Country']);
	}

	return implode(' ', $addr);
}

function aname($name)
{
	$fields = array_only((array)$name, [
		'FirstName', 
		'MiddleNames', 
		'Surname', 
		'Name'
	]);

	return implode(' ', $fields);
}

function app_name()
{
	if ($theme_name = ThemeHelper::appName())
		return $theme_name;
	return config('app.name', 'CMI Data');
}

/**
*
* @note make sure $str_date match the format of $from
*/
function format_str_date($str_date, $from="d/m/Y", $to="Y-m-d H:i:s")
{
	return $str_date ? Carbon::createFromFormat($str_date, $from)->format($to) : $str_date;
}

/**
* Use only if $date is a ISO format
*/
function format_date($date, $format="m/d/Y") 
{
	return $date ? Carbon::parse($date)->format($format) : $date;
}