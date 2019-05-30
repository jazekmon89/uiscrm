<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use StoredProcTrait;

    const CREATED_AT = 'CreatedDateTime';
    const UPDATED_AT = 'ModifiedDateTime';

    protected $dates = [
    	'CreatedDateTime', 'ModifiedDateTime'
    ];

}
