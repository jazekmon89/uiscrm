<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Helpers\OrganisationHelper as Organisation;
use App\Helpers\SearchHelper;
use App\Providers\Facades\Entity;
use App\Http\Controllers\Controller;
use App\Http\Requests\Quotes\RFQRequest;
use App\Helpers\RFQDataSupplier;
use App\Http\Requests\Quotes\RFQFormDataHandler;

use Flash;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\NotesController;
use App\Note;
use App\Http\Controllers\FileAttachmentsController;
use App\Attachment;
use App\Helpers\ClientHelper;
use View;
// use Jenssegers\Agent\Agent;
class RFQController extends Controller
{
    public function userRFQs($RFQID=null) 
    {
        if ($RFQID && is_guid($RFQID) && $RFQ = Entity::get('RFQ', $RFQID, 3)) 
        {
            return view('RFQ.Frontend.details', compact('RFQ'));
        }

        /**
        * Get All User's RFQ using Contact since a user is not a client if no quote or policy given/aquired
        * and if Client, do we need to get all clients first then use Client_GetRFQIDs ?
        */
        $rfqs = Entity::getMultiple('RFQ', Entity::model()->Contact_GetRFQIDs(Auth::user()->contact_id), 3);
    
        $current = $history = [];

        foreach($rfqs as $rfq)
        {
            $Expired = (bool)Carbon::parse($rfq['ExpiryDate'])->diff(Carbon::now()->subDays(30))->s;

            if ($Expired)
            {
                $history[] = $rfq;
            }
            else
            {
                $current[] = $rfq;
            }
        }
    
        return view('RFQ.Frontend.index', compact('current', 'history'));
    }

    /**
    * Backend display
    */
    public function index(SearchHelper $helper, Request $request) 
    {   
        $params = $helper->getSearchParams($request, 'FindRFQByRFQContactLeadAndBusinessDetails');
        $current = $request->status !== 'history';

        if (!$current)
        {
            $params[11] = Carbon::now()->subDays(1)->format('Ymd');
        }
        else 
        {
            $params[10] = Carbon::now()->format('Ymd');

        }
        
        $searchs = [$params];

        if ($request->PolicyTypeID)
        {
            $Policies = array_values(collect(Organisation::getPolicyTypes($request->OrganisationID))
                ->filter(function($Organisation, $key) use ($request) {
                    return strpos(strtolower($Organisation->DisplayText), strtolower($request->PolicyTypeID)) !== false;
                })->toArray());
            
            if (empty($Policies))
            {
                return response()->json([]);
            }
            
            for($i = 0; $i < count($Policies); $i++)
            {
                if (!$i)
                {
                    continue;
                }

                $searchs[] = $params;
            }    
            
            
            foreach($Policies as $key => $Policy)
            {
                $searchs[$key][1] = $Policy->PolicyTypeID;  
            }         
        }

        if ($request->RFQStatusID)
        {
            $statuses = array_values(collect(Entity::model()->RFQStatus_GetRFQStatuses())
                ->filter(function($status, $key) use ($request) {
                    return strpos(strtolower($status->DisplayText), strtolower($request->RFQStatusID)) !== false;
                })->toArray());

            if (empty($statuses))
            {
            
                return response()->json([]);
            }
            
            $_search = $searchs;
            $_count = count($statuses);
            for($i = 0; $i < $_count; $i++)
            {
                if (!$i)
                {
                    continue;
                }

                $searchs = array_merge($searchs, $_search);
            }      
            $_scount = count($searchs);
            foreach($searchs as $key => $search)
            {
                $i = (int)floor($key / ($_scount / $_count));
                $status = array_get($statuses, $i);

                if ($status);
                    $searchs[$key][2] =  $status->RFQStatusID;
            }    
        }
        $rfqs = []; /*Entity::getMultiple('RFQ', Entity::model()->Organisation_GetRFQIDs('DE09F4B6-C708-4F5F-A48E-432AF31E4D74'), 3);*/
        foreach($searchs as $search)
        {
            $results = $helper->search('FindRFQByRFQContactLeadAndBusinessDetails', $search);

            foreach($results as $rfq) 
            {
                if (!arr_lfind($rfqs, "RFQID", $rfq->RFQID))
                {
                    $rfqs[] = (array)$rfq;
                }
            }
        }

        if ($request->expectsJson() 
        || $request->ajax == 1
        || $request->route() && $request->route()->getPrefix() === 'api')
        {
            return response()->json($rfqs);
        }

        return view('RFQ.Backend.index', compact('current', 'rfqs'));
    }

    /**
    * Backend display
    */
    public function edit(Request $request, $RFQID) 
    {
        if (is_guid($RFQID) && $RFQ = Entity::get('RFQ', $RFQID, 3)) 
        {
            $Organisation = (object)array_get($RFQ, 'Organisation');
            $PolicyType = (object)array_get($RFQ, 'PolicyType');

            app('request')->merge([
                'PolicyTypeID' => $PolicyType->PolicyTypeID,
                'FormTypeID' => $PolicyType->RFQFormTypeID,
                'OrganisationID' => $Organisation->OrganisationID
            ]);
            if(!isset($RFQ['InsurableBusiness']['PostalAddress']['AddressLine1']) 
            && isset($RFQ['InsurableBusiness']['PostalAddress']['Address1']))
                $RFQ['InsurableBusiness']['PostalAddress']['AddressLine1'] = $RFQ['InsurableBusiness']['PostalAddress']['Address1'];
            if(!isset($RFQ['InsurableBusiness']['PostalAddress']['AddressLine2'])
            && !isset($RFQ['InsurableBusiness']['PostalAddress']['Address2']))
                $RFQ['InsurableBusiness']['PostalAddress']['AddressLine2'] = $RFQ['InsurableBusiness']['PostalAddress']['Address2'];
            
            $PolicyDisplayText = array_get($PolicyType, "DisplayText");
            if(!$PolicyDisplayText){
                $PolicyDisplayText = array_get((array)$PolicyType, "DisplayText");
            }
            View::share('disptext', $PolicyDisplayText);

            $FormRequest = app(RFQRequest::class);
            $html = $FormRequest->generateHtml(false, [new RFQDataSupplier($FormRequest, $RFQ), 'supply']);

            $tc = new TasksController();
            $nc = new NotesController(new Note);
            $fc = new FileAttachmentsController(new Attachment);
            $EntityName = 'RFQ';
            $ParentID = $RFQID;
            $TaskTypeID = null;

            list($task_content, $task_buttons, $css, $js) = $tc->createTaskInterface(
                $EntityName, 
                $ParentID, 
                null, 
                $Organisation->OrganisationID, 
                $TaskTypeID, 
                true, 
                true, 
                true, 
                true
            );
            list($note_content, $note_buttons, $css_notes, $js_notes) = $nc->notesInterface(
                $EntityName,
                $ParentID,
                true,
                false,
                true
            );

            list($attachment_content, $attachment_buttons, $css_files, $js_files) = $fc->attachmentsInterface(
                $EntityName,
                $ParentID,
                true,
                true,
                false
            );
            $css = array_merge($css, $css_notes, $css_files);
            $js = array_merge($js, $js_notes, $js_files);
            
            $action = $request->action ?: $segments = $request->segment(3, 'view');

            return view('RFQ.Backend.details', compact(
                'html', 
                'RFQ',
                'task_content', 
                'task_buttons', 
                'EntityName', 
                'ParentID', 
                'TaskTypeID', 
                'css', 
                'js',
                'note_content',
                'note_buttons',
                'attachment_content',
                'RFQID',
                'action'
            ));
        }

        abort(404);
    }

    public function reCreateRFQ(Request $request, $RFQID, $ContactUserID=null)
    {
        if (!is_guid($RFQID) || !$RFQ = Entity::get('RFQ', $RFQID, 3)) 
        {
            return response()->json(['error' => 'RFQ does not exists.']);
        }

        $Organisation = (object)array_get($RFQ, 'Organisation');
        $PolicyType = (object)array_get($RFQ, 'PolicyType');

        $request->merge([
            'PolicyTypeID' => $PolicyType->PolicyTypeID,
            'FormTypeID' => $PolicyType->RFQFormTypeID,
            'OrganisationID' => $Organisation->OrganisationID
        ]);

        $data = $request->all();
        $handler = app(RFQFormDataHandler::class);
        $form_request = $handler->request();

        $form_request->validateApiData($data);
        if ($error = $form_request->errors())
            return response()->json($error);

        if ($ContactUserID && is_guid($ContactUserID))
            $handler->setUserContact($ContactUserID);

        $handler->setReferenceRFQ($RFQ);
        $handler->setData($data);   

        // disable email notification
        $handler->notify = false;

        if ($RFQ = $handler->save())
        {
            if ($ContactUserID)
            {
                Flash::success("A new RFQ with a matched contact was created. Reference # {$RFQ['RFQRefNum']}");
            }
            else
            {
                Flash::success("A new RFQ was created with a reference # {$RFQ['RFQRefNum']}");
            }

            return response()->json(['success' => true] + $RFQ);
        }
    
        return response()->json(['error' => 'Error saving form data', 'data' => $request->all()]);
    }

    public function createContact(Request $request)
    {   
        $Contact = $request->Input('Contact');
        $HomeAddress = $request->Input('HomeAddress');
        $MailAddress = $request->Input('MailAddress');
        $UseHomeAddr = $request->Input("use_home_addr") === 'Y';
        
        $validator = Validator::make($Contact, [
            'FirstName' => 'required',
            'Surname' => 'required',
            'EmailAddress' => 'required|email',
            'BirthDate' => 'nullable|date_format:d/m/Y',
            'BirthCountry' => 'required',
            'BirthCity' => 'required',
            'MobilePhoneNumber' => 'nullable|numeric'
        ]);

        $errors = [];

        if ($validator->fails())
        {
            $errors['Contact'] = $validator->getMessageBag()->toArray();
        }

        $address_rules = [
            //'StreetNumber' => 'required',
            //'StreetName' => 'required',
            'AddressLine1' => 'required',
            'Country' => 'required',
            'Postcode' => 'required',
            'State' => 'required|in:'. implode(',', array_keys(all_states()))
        ];

        $validator = Validator::make($HomeAddress, $address_rules);

        if ($validator->fails())
        {
            $errors['HomeAddress'] = $validator->getMessageBag()->toArray();
        }

        if (!$UseHomeAddr) 
        {
            $validator = Validator::make($MailAddress, $address_rules);

            if ($validator->fails())
            {
                $errors['MailAddress'] = $validator->getMessageBag()->toArray();
            }
        }

        if (!empty($errors))
        {
            return response()->json($errors);
        }
       
        $HomeAddress = Entity::model()->CreateAddress_first([
            //array_get($HomeAddress, 'UnitNumber'),
            //array_get($HomeAddress, 'StreetNumber'),
            //array_get($HomeAddress, 'StreetName'),
            array_get($HomeAddress, 'AddressLine1'),
            array_get($HomeAddress, 'AddressLine2'),
            array_get($HomeAddress, 'State'),
            array_get($HomeAddress, 'Postcode'),
            array_get($HomeAddress, 'Country'),
            null
        ], ['AddressID' => 'uniqueidentifier']);

        if (!$UseHomeAddr) 
        {
            $MailAddress = Entity::model()->CreateAddress_first([
                //array_get($MailAddress, 'UnitNumber'),
                //array_get($MailAddress, 'StreetNumber'),
                //array_get($MailAddress, 'StreetName'),
                array_get($MailAddress, 'AddressLine1'),
                array_get($MailAddress, 'AddressLine2'),
                array_get($MailAddress, 'State'),
                array_get($MailAddress, 'Postcode'),
                array_get($MailAddress, 'Country'),
                null
            ], ['AddressID' => 'uniqueidentifier']);
        }
        else
        {
            $MailAddress = $HomeAddress;
        }
        
        if ($Contact = Entity::model()->CreateContactWithoutLogin_first([
            array_get($Contact, 'FirstName'),
            array_get($Contact, 'MiddleNames'),
            array_get($Contact, 'Surname'),
            array_get($Contact, 'PreferredName'),
            $HomeAddress->AddressID,
            $MailAddress->AddressID,
            array_get($Contact, 'EmailAddress'),
            array_get($Contact, 'MobilePhoneNumber'),
            format_str_date(array_get($Contact, 'BirthDate'), "Y-m-d 00:00:00"),
            array_get($Contact, 'BirthCountry'),
            array_get($Contact, 'BirthCity'),
            Auth::check() ? Auth::id() : null
        ], ['ContactID' => 'uniqueidentifier'])) {
            return response()->json(['success' => true] + $Contact = Entity::get('Contact', $Contact->ContactID));
        }

        return response()->json(Entity::model()->getLastSpError());
    }

    public function uploadQuotesFromCSV(Request $request)
    {
        $file = $request->file('file');

        $validator = Validator::make([
            'file' => $file,
            'extension' => strtolower($file->getClientOriginalExtension()),
        ], [
            'file' => 'required',
            'extension' => 'required|in:csv'
        ]);

        if ($validator->fails()) {
            response()->json(['error' => $validator->getMessageBag()->toArray()]);
        }

        if ($handle = @fopen($file->getPathname(), 'r')) {
            $result = ['body' => []];
            while($data = fgetcsv($handle, 1000, ',')) {
                if (empty($result['headers'])) {
                    $result['headers'] = $data;
                }
                else {
                    $row = [];
                    foreach($result['headers'] as $key => $header) {
                        $row[$header] = $data[$key];    
                    }
                    $result['body'][] = $row;    
                } 
            }
            if (!empty($result['body']))
                return response()->json($result);
        }

        return response()->json(['error' => 'Either the file is not readable or empty.']);
    }

    public function versions($RFQID, $VersionID=null)
    {
        if (is_guid($RFQID) && $RFQ = Entity::get('RFQ', $RFQID, 3))
        {
            if ($VersionID && is_guid($VersionID) && $Version = Entity::get('RFQ', $VersionID, 3))
            {
                $Organisation = (object)array_get($Version, 'Organisation');
                $PolicyType = (object)array_get($Version, 'PolicyType');
                
                $PolicyDisplayText = array_get($PolicyType, "DisplayText");
                if(!$PolicyDisplayText){
                    $PolicyDisplayText = array_get((array)$PolicyType, "DisplayText");
                }
                View::share('disptext', $PolicyDisplayText);

                app('request')->merge([
                    'PolicyTypeID' => $PolicyType->PolicyTypeID,
                    'FormTypeID' => $PolicyType->RFQFormTypeID,
                    'OrganisationID' => $Organisation->OrganisationID
                ]);

                $FormRequest = app(RFQRequest::class);
                $html = $FormRequest->generateHtml(false, [new RFQDataSupplier($FormRequest, $Version), 'supply']);

                return view("RFQ.Backend.version-details", compact('RFQ', 'RFQID', 'Version' ,'VersionID', 'html'));   
            }
            else {
                $versions = Entity::model()->RFQ_GetVersions($RFQID);

                arr_usort($versions, 'Version');
            }

            return view("RFQ.Backend.versions", compact('versions', 'RFQ', 'RFQID'));
        }

        abort(404);
    }

    public function nonProceed(Request $request, $RFQID) 
    {
        if (is_guid($RFQID) && $RFQ = Entity::get('RFQ', $RFQID, 3))
        {
            $statuses = Entity::model()->RFQStatus_GetRFQStatuses();
            $updated = false;

            if ($non_proceed = arr_lfind($statuses, 'Name', 'DidNotProceed'))
            {
                
                $updated = Entity::model()->RFQ_UpdateStatus_first([
                    $RFQID, 
                    $non_proceed->RFQStatusID,
                    Auth::check() ? Auth::id() : null
                ]);
            }    

            return response()->json(['success' => (bool)$updated]);
        }

        return response()->json(['error' => 'RFQ not found']);
    }


    public function tasks($RFQID, ClientHelper $ch){ /*orly*/
        if ($RFQID && is_guid($RFQID) && $RFQ = Entity::get('RFQ', $RFQID, 3)) 
        {
            $title = 'Tasks';
            $Organisation = (object)array_get($RFQ, 'Organisation');

            $tc = new TasksController();
            $EntityName = 'RFQ';
            $ParentID = $RFQID;
            $TaskTypeID = null;

            list($task_content, $task_buttons, $css, $js) = $tc->createTaskInterface(
                $EntityName, 
                $ParentID, 
                null, 
                $Organisation->OrganisationID, 
                $TaskTypeID,
                true, 
                false, 
                false, 
                false,
                true
            );
            return view("RFQ.Backend.tasks", compact('task_content', 'task_buttons', 'css', 'js', 'title', 'RFQID', 'RFQ'));
        }

        abort(404);
    }

    public function notes($RFQID, ClientHelper $ch){

        if (!$RFQID || !is_guid($RFQID) || !$RFQ = Entity::get('RFQ', $RFQID, 3))
        {
            abort(404);
        }

        $title = 'Notes';
        $nc = new NotesController(new Note);
        $EntityName = 'RFQ';
        $ParentID = $RFQID;

        list($note_content, $note_buttons, $css, $js) = $nc->notesInterface(
            $EntityName,
            $ParentID,
            true,
            true
        );
        return view("RFQ.Backend.notes", compact('note_content', 'note_buttons', 'css', 'js', 'title', 'RFQID', 'RFQ'));
    }

    public function attachments($RFQID, ClientHelper $ch){

        if (!$RFQID || !is_guid($RFQID) || !$RFQ = Entity::get('RFQ', $RFQID, 3))
        {
            abort(404);
        }

        $title = 'Attachments';
        $fc = new FileAttachmentsController(new Attachment);
        $EntityName = 'RFQ';
        $ParentID = $RFQID;

        list($attachment_content, $attachment_buttons, $css, $js) = $fc->attachmentsInterface(
            $EntityName,
            $ParentID,
            true,
            false,
            true
        );
        return view("RFQ.Backend.attachments", compact('attachment_content', 'attachment_buttons', 'css', 'js', 'title', 'RFQID', 'RFQ'));
    }

    public function updateExpiryDate(Request $request = null) {
        $data = $request->all();
        $user_id = Auth::id();

        $expiryDate_data = [
            $data['RFQID'],
            $data['ExpiryDate'],
            $user_id
        ];
        //dd($expiryDate_data);

        $res = Entity::model()->RFQ_UpdateExpiryDate($expiryDate_data);

        if(!$res){
            Flash::error(trans('messages.expirydate_failed_error'));
            return back();
        }

        Flash::success(trans('messages.expirydate_update_completed'));
        return back();
    }

}
