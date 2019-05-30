<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Search extends Model
{
	use StoredProcTrait;

	public $params = [
		'FindInsuranceEntitiesByInsuranceDetails' => [
			'BrokerRefNum',
			'QuoteNum', 
			'PolicyNum',
			'InsuredName',
			'InvoiceNum',
			'TradingName',
			'MotorVehicleRegNum'
		],
		'Address_Find' => [
			//'UnitNumber',
			//'StreetNumber',
			//'StreetName',
			'AddressLine1',
			'AddressLine2',
			'City',
			'State',
			'Postcode',
			'Country'
		],
		'FindContactByPersonalDetails' => [
			'ContactRefNum',
			'FirstName',
			'MiddleNames',
			'Surname',
			'PreferredName',
			'EmailAddress',
			'MobilePhoneNumber',
			'AddressID',
			'ModifiedDate'
		],
		'FindClientByClientAndBusinessDetails' => [
			'ClientRefNum',
			'InsuredName',
			'TradingName',
			'AustralianBusinessNumber',
			'IsRegisteredForGST',
			'BusinessStructureTypeID',
			'PostalAddressID'
		],
		'FindRFQByRFQContactLeadAndBusinessDetails' => [
			'RFQRefNum',
			'PolicyTypeID',
			'RFQStatusID',
			'InsuredName',
			'Name',
			'Address',
			'LodgementDate',
			'PhoneNumber',
			'EmailAddress',
			'BusinessAddress',
			'ExpiryDateFrom',
			'ExpiryDateTo'		
		],
		'FindInsuranceQuoteByInsuranceQuoteDetails' => [
			'RFQRefNum',
			'PolicyTypeID',
			'InsuredName',
			'QuoteRefNum',
			'InsuranceQuoteStatusID',
			'InvoiceNum',
			'Classification',
			'UnderWriterID',
			'Premium',
			'CoverStartDate',
			'ExpiryDateFrom',
			'ExpiryDateTo'	
		]
	];

	public function getSearchParams(Request $request, $method, $parent="") {
		$params = array_get($this->params, $method);
		$bag 	= $request->all();

		if ($params) {
			foreach ($params as $param) {
				$value = array_get($bag, $parent . $param);

				if (preg_match('/(Date)/', $param) && $value) {
					$value = Carbon::createFromFormat("d-m-Y", $value)->format("Y-m-d H:i:s");
				}

				// make sure to pass null if empty 
				$data[] = $value ?: null;
			}
			return $data;
	}
	return [];
	}
    
}
