<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use App\Http\Requests\Quotes\RFQRequest;
use App\Providers\Facades\Entity;

use Carbon\Carbon;

class RFQDataSupplier
{
	public function __construct(RFQRequest $request, $RFQ)
	{
		$this->request = $request;
		$this->RFQ = $RFQ;
	}

	public function supply(Request $request)
	{
		$this->supplyContactDetailsData($request);
		$this->supplyGroups($request);
		$this->supplyClaims($request);
	}

	protected function supplyContactDetailsData($request)
	{
		$ContactDetails = [
			// Contact|Lead
			'RFQ' => [
				'InsuredName' => array_get($this->RFQ, "InsuredName"),
				'RequesterName' => aname(array_get($this->RFQ, "Contact", array_get($this->RFQ, "Lead"))),
				'EmailAddress' => array_get($this->RFQ, "Contact.EmailAddress", array_get($this->RFQ, "Lead.EmailAddress")),
				'PhoneNumber' => array_get($this->RFQ, "Contact.MobilePhoneNumber", array_get($this->RFQ, "Lead.PhoneNumber")),
			],
			"Business" => [
				"InsurableBusinessID" => array_get($this->RFQ, "InsurableBusinessID"),
				"BusinessStructureTypeID" => array_get($this->RFQ, "InsurableBusiness.BusinessStructureTypeID"),
				"TradingName" => array_get($this->RFQ, "InsurableBusiness.TradingName"),
				"AustralianBusinessNumber" => array_get($this->RFQ, "InsurableBusiness.AustralianBusinessNumber"),
				"IsRegisteredForGST" => array_get($this->RFQ, "InsurableBusiness.IsRegisteredForGST"),
			],
			//mailing address
			"mail_addr" => array_get($this->RFQ, 'InsurableBusiness.PostalAddress')
		];

		$request->merge(['ContactDetails' => $ContactDetails]);
	}

	protected function supplyGroups($request)
	{
		$data = [];
		foreach($this->request->groups(false) as $group)
		{	
			$this->supplyGroupAnswers($group, $group->Name, $data);
		}
		$request->merge($data);
	}

	protected function supplyGroupAnswers($group, $baseKey='', &$storage=[])
	{
		if (!empty($group->questions))
		{
			foreach($group->questions as $question)
			{
				if ($answers = $this->model()->RFQ_GetFormQuestionAnswersByQnName([$this->RFQ['RFQID'], $question->Name]))
				{
					foreach((array)$answers as $key => $answer) 
					{

						switch($question->FormQuestionTypeName)
						{
							case 'Address':
								if ($AddressID = array_get((array)$answer, 'AnswerAddressID'))
								{
									$value = Entity::get('Address', $AddressID);
								}
							break;
							default:
								if (in_array($question->FormQuestionTypeName, ['SelectOne', 'SelectMulti']))
								{
									$Answerkey = 'FormQuestionPossChoiceID';
								}
								else if ($question->FormQuestionTypeName == 'Date') 
								{
									$Answerkey = 'AnswerDateTime';
								}
								else {
									$Answerkey = 'Answer'. $question->FormQuestionTypeName;
								}
								$value = array_get((array)$answer, $Answerkey);
							break;
						}
						
						if ($value)
						{
							// group reapting group answers
							$k = $group->IsRepeating === 'Y' ? guid_row($answer->FormQuestionGroupInstanceID) : '';

							$index = $baseKey . ($k !== '' ? ".$k" : "") . ".{$question->FormQuestionID}";
							
							if ($question->FormQuestionTypeName === 'SelectMulti')
								$index .= ".{$value}";

							if ($question->FormQuestionTypeName === 'Date')
							{
								
								if (preg_match("#(birthdate|birth)#i", $question->Name))
								{
									$value = Carbon::parse($value)->format('d/m/Y');
								}
							}

							array_set($storage, $index, $value);
						}
						
					}
				}
			}
		}
		
		if (!empty($group->children))
		{
			foreach($group->children as $subgroup)
			{
				$this->supplyGroupAnswers($subgroup, $baseKey . ".{$subgroup->Name}", $storage);
			}
		}
	}

	protected function supplyClaims($request)
	{

		$Claims = Entity::getMultiple('PreviousClaim', (array)$this->model()->RFQ_GetPreviousClaims($this->RFQ['RFQID']));
		$data = [];
		
		foreach($Claims as $key => $Claim)
		{
			/*if (!empty($Claim['InsurancePeriodBeginDate']) && $Claim['InsurancePeriodBeginDate']) {
				$Claim['InsurancePeriodBeginDate'] = implode('/', $this->extractClaimDate($Claim['InsurancePeriodBeginDate']));
			}
			if (!empty($Claim['InsurancePeriodEndDate']) && $Claim['InsurancePeriodEndDate']) {
				$Claim['InsurancePeriodEndDate'] = implode('/', $this->extractClaimDate($Claim['InsurancePeriodEndDate']));
			}*/
			if (!empty($Claim['ClaimDate']) && $Claim['ClaimDate'])
				$Claim['InsuranceAccidentDate'] = implode('/', $this->extractClaimDate( date('m/Y', strtotime($Claim['ClaimDate']) )));

			$category = is_guid($Claim['TypeOfClaim']) ? "Claims" : "OtherClaims";

			array_set($data, "Claims.$category.$key", $Claim);
		}
		$request->merge($data);
	}


	protected function extractClaimDate($datastring)
	{
		return [
			/*'month' => */substr($datastring, 0, 2),
			/*'year' => */substr($datastring, 2),
		];
	}

	protected function model() 
	{
		return $this->request->helper();
	}
}