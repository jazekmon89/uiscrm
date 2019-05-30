<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\StoredProcTrait as StoredProcTrait;

use DB;

class Claims  extends Model
{
	use StoredProcTrait;

	public $fillable = [
		'ClaimNum',
		'ClaimTypeID',
		'InsurancePolicyID',
		'GSTPct',
		'AltContactName',
		'AltPhoneNumber',
		'EventDateTime',
		'EventDescription',
		'AdditionalComments',
		'IsDeclaredTrueByClaimant',
		'IsTermsAcceptedByClaimant',
		'ClaimantSignatureName',
		'UnderwriterPhoneNumber',
		'ClaimStatusID',
		'CurrentUserID',
    ];
}