<?php

namespace App;

use DB;
use Flash;
use App\StoredProcTrait as StoredProc;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, StoredProc;

    protected $_is_client = null;

    protected $_is_adviser = null;

    protected $_home_address = null;

    protected $_mail_address = null;

    protected $_businesses = null;

    protected $_clients = null;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'surname', 'city', 'postcode', 'email', 'mobilephonenumber', 
        //'birth_date', 'unit_number', 'street_number', 'street_name', 'state', 'post_code',
        'birth_date', 'address_line_1', 'address_line_2', 'state', 'post_code',
        'provider', 'data', 'contact_id', 'user_id', 'middle_name', 'preffered_name', 'home_address_id',
        'postal_address_id', 'country', 'contact_ref_num', 'created_by', 'created_date_time', 'modified_by',
        'modified_date_time','username','password',
    ];

    public $appends = ['name', 'is_client', 'is_adviser', 'home_address', 'mail_address', 'businesses', 'clients'];

    public $casts = [
        'id' => 'string'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function findByProviderAndEmail($provider=null, $email=null) {
        $this->provider = $provider ?: $this->provider;
        $this->email    = $email ?: $this->email;
        
        $auth = $this->OAuthUser_GetUserIDByOAuthUserIdent_first([$this->provider, $this->email], ['UserID' => 'uniqueidentifier']);
        
        if ($auth && property_exists($auth, 'UserID') && $auth->UserID)
            $this->attachContactDetails($this->id = $auth->UserID);
        elseif ($auth && property_exists($auth, 'UserID') && !$auth->UserID)
            Flash::error(trans('messages.login_failed2'));
        elseif (!$auth) Flash::error(trans('messages.login_failed'));
        return $this;   
    }

    public function findByUsernamePassword($username=null, $password=null){

        $this->username = $username ?: $this->username;
        $this->password    = $password ?: $this->password;
        
        $auth = $this->User_AuthenticateByUsernameAndPassword_first([$this->username, $this->password], ['UserID' => 'uniqueidentifier']);
        if ($auth) $this->attachContactDetails($this->id = $auth->UserID);
        elseif (!$auth) Flash::error(trans('messages.login_failed'));
        return $this;
    }

    private function attachContactDetails($userid){
       if ($contact = $this->Contact_GetByUserID_first($this->id)) {
            $this->contact_id           = $contact->ContactID;
            $this->user_id              = $userid;
            $this->first_name           = $contact->FirstName;
            $this->middle_name          = $contact->MiddleNames;
            $this->surname              = $contact->Surname;
            $this->preferred_name       = $contact->PreferredName;
            $this->home_address_id      = $contact->HomeAddressID;
            $this->postal_address_id    = $contact->PostalAddressID;
            $this->email                = $contact->EmailAddress;
            $this->mobile_phone_number  = $contact->MobilePhoneNumber;
            $this->birth_date           = $contact->BirthDate;
            $this->city                 = $contact->BirthCity;
            $this->country              = $contact->BirthCountry;
            $this->contact_ref_num      = $contact->ContactRefNum;
            $this->created_by           = $contact->CreatedBy;
            $this->created_date_time    = $contact->CreatedDateTime;
            $this->modified_by          = $contact->ModifiedBy;
            $this->dodified_date_time   = $contact->ModifiedDateTime;

            // run role checking for client ?
            $this->is_client;
        }
        return $this;
    }

    public function getNameAttribute() {
        $name = [$this->first_name, $this->surname];

        if (empty($name)) $name = [$this->email];
        return implode(' ', $name);
    }   

    public function getAuthIdentifier() {
        return $this->id;
    }

    public function getIsClientAttribute() {
        if ($this->_is_client !== null)
            return $this->_is_client;
        return $this->_is_client = $this->User_IsMemberOfRole_first([$this->id, 'Client'], ['IsMemberOfRole' => 'varchar'])->IsMemberOfRole === 'Y';
    }
    public function getIsAdviserAttribute() {
        if ($this->_is_adviser !== null)
            return $this->_is_adviser;
        return $this->_is_adviser = $this->User_IsMemberOfRole_first([$this->id, 'Adviser'], ['IsMemberOfRole' => 'varchar'])->IsMemberOfRole === 'Y';
    }

    public function getHomeAddressAttribute() {
        if ($this->_home_address !== null)
            return $this->_home_address;
        return $this->_home_address = $this->home_address_id ? $this->Address_Get_first($this->home_address_id) : null;
    }

    public function getMailAddressAttribute() {
        if ($this->_mail_address !== null)
            return $this->_mail_address;
        return $this->_mail_address = $this->postal_address_id ? $this->Address_Get_first($this->postal_address_id) : null;
    }


    public function getClientsAttribute() {
        if ($this->_clients !== null) {
            return $this->_clients;
        }
        $clientIDs = (array)$this->Contact_GetClientIDs($this->contact_id);
        $clients = [];
        foreach($clientIDs as $clientID) {
            if ($client = $this->Client_Get_first($clientID->ClientID)) {
                $clients[] = (array)$client;
            }
        }
        return $this->_clients = $clients;
    }
}
