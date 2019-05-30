<?php 

namespace App\Helpers;

use App\Helpers\UserHelper;
use App\Providers\Facades\Entity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class FormHelper{

    public function getCurrentUser() {
        return Auth::check() ? Auth::user() : null;
    }
    
    public function answerByType(){
    	
    }

    public function __call($method, $params) {
        return call_user_func_array([Entity::model(), $method], $params);
    }

    

}