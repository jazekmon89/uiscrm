<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use StoredProcTrait;

    const CREATED_AT = 'CreatedDateTime';
    const UPDATED_AT = 'ModifiedDateTime';

    protected $fillable = [
    	'AddressID', 'AddressLine1', 'AddressLine2', //'UnitNumber', 'StreetNumber', 'StreetName', 
        'City', 'State', 'Postcode', 'Country',
    	'CreatedBy', 'CreatedDateTime', 'ModifiedBy', 'ModifiedDateTime'
    ];

    protected $dates = [
    	'CreatedDateTime', 'ModifiedDateTime'
    ];

}
