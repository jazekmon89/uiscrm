<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use App\Events\SubmitInquiry as SubmitInquiry;

class Inquiry extends Model
{
    use  StoredProcTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UserID', 'Description', 'InquirerName', 'InquirerEmailAddress', 'InquirerPhoneNumber'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'UserID'
    ];

    public function SubmitInquiry()
    {
        foreach($this->fillable as $field) $data[] = $this->$field;

        $res = $this->setProcedure('SubmitInquiry')->setProcedureData($data)->getSPResult();
        if ($res) {
            event(new SubmitInquiry($this));
        }

        return $res;
    }
}
