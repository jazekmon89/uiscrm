<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Quotes\RFQRequest;
use App\Http\Requests\Quotes\RFQFormDataHandler;
use App\Helpers\PolicyHelper;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class QuotesApiController extends Controller
{
	public function __construct(PolicyHelper $helper) {
		$this->helper = $helper;

        // we need session, csrf protection etc
        $this->middleware('web');
	}

	/**
	 * @return json form details data not the html output
	 */
	public function getForm($PolicyTypeID, $OrgranizationID=null) {

        // check from all TYPE/Forms
        if ($Forms = $this->helper->getTypes($OrgranizationID))
            $Form = arr_lfind($Forms, 'PolicyTypeID', $PolicyTypeID);

        // get POLICY FORM
        else ($Form = $this->helper->PolicyType_GetRFQFormTypeID_first($PolicyTypeID, ['FormTypeID' => 'uniqueidentifier']));

        if (!$Form) return response()->json($this->helper->getSpErrors());

        $Form->url = route('api.quotes.html', ['generate' => 1] + (array)$Form);

        $form = $this->helper->getForm($PolicyTypeID, $Form->FormTypeID, $OrgranizationID);

        $Form->Groups = array_get($form, 'Groups', []);

        $Form->Covers = $this->helper->getPolicyCovers($PolicyTypeID, $OrgranizationID);

        $Form->Questions = $this->helper->getAllQuestions($PolicyTypeID);

		return response()->json($Form);
	}   

	/**
	 * @return json forms and form details data
	 */
    public function forms($OrgranizationID=null) {
        $Org   = $OrgranizationID ?: $this->helper->getDefaultOrganisation();
        $forms = (array)$this->helper->getTypes($Org);

        foreach($forms as $form) {
            $form->OrgranizationID = $Org;
            $form->url = route('api.quotes.html', ['generate' => 1] + (array)$form);
        }
 
    	return response()->json($forms ?: $this->helper->getSpErrors());
    }	

    public function groups($PolicyTypeID, $FormTypeID, $recursive=true) {
    	/**
    	* @todo validate Form if a valid PolicyType Form
    	* @see PolicyHelper::isPolicyForm
    	*/
    	$groups = (array)$this->helper->getFormTopLevelGroups($FormTypeID);

    	if ($recursive) {
    		foreach($groups as &$group) {
    			$group->children = $this->helper->getGroupChildren($group->FormQuestionGroupID);
    		}
    	}
    	return response()->json($groups ?: $this->helper->getSpErrors());
    }

    public function html(RFQRequest $request, $PolicyTypeID, $FormTypeID, $generate=false) {
        /** 
         * @see App\Http\Requests\Quotes\RFQRequset 
         */
        return $request->generateHtml($generate);
    }

    public function validateForm(RFQRequest $request, $PolicyTypeID, $FormTypeID, $GroupID) {

        if ($request->validateGroup()) {
            // $group = $request->group();    

          
            // // save per GroupID per PolicyID:FormID so that we have unique set of data for 
            // // client to be able to answer other forms also
            // session(["RFQAPI.{$PolicyTypeID}.{$FormTypeID}.{$GroupID}" => $request->data()], 2800);

            // if (isset($group->partials) && $group->partials) {
            //     foreach($group->partials as $partial => $tmpl) {
            //         if ($data = $request->Input($partial)) {
            //             session(["RFQAPI.{$PolicyTypeID}.{$FormTypeID}.{$partial}" => $data], 2800);
            //         }
            //     }
            // }

            return response()->json(["success" => true]);
        }
    }

    public function submit(RFQRequest $request, $PolicyTypeID, $FormTypeID) {

        $request->validateApiData($request->all());

        if ($error = $request->errors())
            return response()->json($error);

        $handler = (new RFQFormDataHandler($request, $this->helper))->setData($request->all());

        if ($RFQRefNum = $handler->save())
            return response()->json(['success' => true, 'RFQRefNum' => $RFQRefNum]);
    
        return response()->json(['error' => 'Error saving form data']);
    }
}
