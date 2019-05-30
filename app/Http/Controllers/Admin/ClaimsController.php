<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Validator;
use App\Helpers\OrganisationHelper as Organisation;
use App\Helpers\PolicyHelper;
use App\Helpers\ClientHelper;
use App\Http\Controllers\Controller;
use App\Attachment;
use App\Claims;
use DateTime;
use App\Http\Controllers\TasksController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Support\Collection;
use Flash;

class ClaimsController extends Controller
{
	protected $claims = null, $policy = null, $client = null, $tasks = null;

    public function __construct(ClientHelper $client, PolicyHelper $helper) {
        $this->client = $client;
        $this->policy = $helper;
        $this->claims = new Claims;
        $this->middleware('adminaccess');
        $this->tasks = new TasksController($helper);
    }

    public function index($EntityName, $ParentID, $TaskTypeID = null){
        list($task_content, $css, $js) = $this->tasks->createTaskInterface($EntityName, $ParentID, $TaskTypeID);
        return view('uis.Claims.Admin.index', compact('task_content', 'EntityName', 'ParentID', 'TaskTypeID', 'css', 'js'));
    }

    public function toList($items, $InsurancePolicyID, $PolicyTypeID){
        $to_return = [];
        foreach($items as $k=>$i){
            $to_return = "<option value='".$i->ClaimTypeID."' url='".route('claims-'.strtolower($i->Name).'-form', [$InsurancePolicyID, $PolicyTypeID])."'>".$i->DisplayText."</option>";
        }
        return $to_return;
    }

    protected function validator($data){
        $validations = [];
        return Validator::make($data, $validations);
    }
}

?>