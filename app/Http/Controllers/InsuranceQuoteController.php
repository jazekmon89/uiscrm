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
use App\Helpers\PolicyHelper;

use View;

class InsuranceQuoteController extends Controller
{

    protected $policy = null;

    public function __construct(PolicyHelper $helper, ClientHelper $client) {
        $this->policy = $helper;
        $this->client = $client;
    }


    public function index(SearchHelper $helper, Request $request) 
    {   
        $params = $helper->getSearchParams($request, 'FindInsuranceQuoteByInsuranceQuoteDetails');
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
        $quotes = []; /*Entity::getMultiple('RFQ', Entity::model()->Organisation_GetRFQIDs('DE09F4B6-C708-4F5F-A48E-432AF31E4D74'), 3);*/
        foreach($searchs as $search)
        {

            //print_r($search); 

            $results = $helper->search('FindInsuranceQuoteByInsuranceQuoteDetails', $search);

            foreach($results as $quote) 
            {

                 $insurance_quote = $this->policy->InsuranceQuote_Get_first($quote->InsuranceQuoteID);
                 $client = $this->client->Client_Get_first($insurance_quote->ClientID); 
                 $policy_type = $this->policy->PolicyType_Get_first($insurance_quote->PolicyTypeID);
                 if(!empty($insurance_quote->RFQID)){
                    $rfq_detail = Entity::model()->RFQ_Get_first($insurance_quote->RFQID);
                    $rf_status = Entity::model()->RFQStatus_Get_first($rfq_detail->RFQStatusID);
                }

                 $CoverStartDateTime = date_create($insurance_quote->CoverStartDateTime);
                 $CoverEndDateTime = date_create($insurance_quote->CoverEndDateTime);
                 $EffectiveDateTime = date_create($insurance_quote->EffectiveDateTime);
                 $ExpiryDateTime = date_create($insurance_quote->ExpiryDateTime);

                 $Excess = $insurance_quote->Excess;
                 $ImposedExcess = $insurance_quote->ImposedExcess;

                 /*$insurance_quote->client_details = $client;
                 $insurance_quote->policy_details = $policy_type;
                 $insurance_quote->RFQ = $rfq_detail;
                 $insurance_quote->RFQStatus = $rf_status;*/


                 /*$quotes [] = $insurance_quote;*/

                $quotes [] = array(
                         'InsuranceQuoteID' => $insurance_quote->InsuranceQuoteID,
                         'RFQRefNum' => '',
                         'PolicyType' => $policy_type->DisplayText,
                         'InsuredName' => $client->InsuredName,
                         'QuoteRefNum' => $insurance_quote->QuoteRefNum,
                         'Status' => '',
                         'ExternalSource' => '',
                         'Classification' => $insurance_quote->Classification,
                         'UnderwriterID' => $insurance_quote->UnderwriterID,
                         'Premium' => $insurance_quote->Premium,
                         'CoverStartDateTime' => date_format($CoverStartDateTime, 'd/m/Y'),
                         'CoverEndDateTime' => date_format($CoverEndDateTime, 'd/m/Y'),
                         'EffectiveDateTime' => date_format($EffectiveDateTime, 'd/m/Y'),
                         'ExpiryDateTime' => date_format($ExpiryDateTime, 'd/m/Y'),
                         'RFQRefNum' => isset($rfq_detail->RFQRefNum)?$rfq_detail->RFQRefNum:'',
                         'RFQID' => isset($insurance_quote->RFQID)?$insurance_quote->RFQID:'',
                         'RFQStatus' => isset($rf_status->DisplayText)?$rf_status->DisplayText:'',
                         'Excess' => isset($insurance_quote->Excess)?$insurance_quote->Excess:'',
                         'ImposedExcess' => $insurance_quote->ImposedExcess
                        );
            }
        }

        //dd($quotes); 

        if ($request->expectsJson() 
        || $request->ajax == 1
        || $request->route() && $request->route()->getPrefix() === 'api')
        {
            return response()->json($rfqs);
        }

        //return view('InsuranceQuote.Backend.index', compact('current', 'rfqs'));
        return view("InsuranceQuote.Backend.index", compact('current', 'quotes'));
    }

    public function view(Request $request, $QuoteID)
    {
    	// if (!$QuoteID || !is_guid($QuoteID) || !$Quote = Entity::get('InsuranceQuote', $QuoteID, 2))
    	// {
    	// 	abort(404);
    	// }

    	return view('InsuranceQuote.Backend.view', compact('Quote'));
    }

    /**
    * Backend display
    */
    public function edit(Request $request, $QuoteID) 
    {
        

         if (is_guid($QuoteID) && $Quote = Entity::get('InsuranceQuote', $QuoteID, 4))  {
            $Organisation = (object)array_get($Quote, 'Organisation');
            $PolicyType = (object)array_get($Quote, 'PolicyType');

            app('request')->merge([
                'PolicyTypeID' => $PolicyType->PolicyTypeID,
                'FormTypeID' => $PolicyType->RFQFormTypeID,
                'OrganisationID' => $Organisation->OrganisationID
            ]);


            $PolicyDisplayText = array_get($PolicyType, "DisplayText");
            if(!$PolicyDisplayText){
                $PolicyDisplayText = array_get((array)$PolicyType, "DisplayText");
            }
            View::share('disptext', $PolicyDisplayText);

            //$FormRequest = app(RFQRequest::class);


            //$html = $FormRequest->generateHtml(false, [new RFQDataSupplier($FormRequest, $Quote), 'supply']);

            $tc = new TasksController();
            $nc = new NotesController(new Note);
            $fc = new FileAttachmentsController(new Attachment);
            $EntityName = 'InsuranceQuote';
            $ParentID = $QuoteID;
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

            $rfq_quote_detail = array();
            $rfq_quote_detail_count = 0;
            $rfq_quote_ids = Entity::model()->RFQ_GetQuoteIDs($Quote['RFQID']);

            foreach($rfq_quote_ids as $rfq_quote_id) {
                $insurance_quote = Entity::model()->InsuranceQuote_Get_first($rfq_quote_id->InsuranceQuoteID);
                $underwriter_details = Entity::model()->Underwriter_Get($insurance_quote->UnderwriterID);


                $insurance_quote->currentUserId = Auth::user()->user_id;

                $insurance_quote->UnderwriterDetails = $underwriter_details;


                /*$obj_merged = array_merge((array) $insurance_quote, $underwriter_details);

                dd($obj_merged);*/

               $rfq_quote_detail[$rfq_quote_detail_count] = $insurance_quote;
                $rfq_quote_detail_count++;
            }

            $html = $rfq_quote_detail;

            //dd($html);

            return view('InsuranceQuote.Backend.details', compact(
                'html', 
                'Quote',
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
                'QuoteID',
                'action'
            ));


         }
        abort(404);
    }

    public function tasks($QuoteID){

        if ($QuoteID && is_guid($QuoteID) && $Quote = Entity::get('InsuranceQuote', $QuoteID, 3)) 
        {
            $title = 'Tasks';
            $Organisation = (object)array_get($Quote, 'Organisation');

            $tc = new TasksController();
            $EntityName = 'InsuranceQuote';
            $ParentID = $QuoteID;
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
            return view("InsuranceQuote.Backend.tasks", compact('task_content', 'task_buttons', 'css', 'js', 'title', 'QuoteID', 'Quote'));
        }

        abort(404);
    }

    public function notes($QuoteID){

        if (!$QuoteID || !is_guid($QuoteID) || !$Quote = Entity::get('InsuranceQuote', $QuoteID, 3))
        {
            abort(404);
        }

        $title = 'Notes';
        $nc = new NotesController(new Note);
        $EntityName = 'InsuranceQuote';
        $ParentID = $QuoteID;

        list($note_content, $note_buttons, $css, $js) = $nc->notesInterface(
            $EntityName,
            $ParentID,
            true,
            true
        );
        return view("InsuranceQuote.Backend.notes", compact('note_content', 'note_buttons', 'css', 'js', 'title', 'QuoteID', 'Quote'));
    }

    public function attachments($QuoteID){

        if (!$QuoteID || !is_guid($QuoteID) || !$Quote = Entity::get('InsuranceQuote', $QuoteID, 3))
        {
            abort(404);
        }

        $title = 'Attachments';
        $fc = new FileAttachmentsController(new Attachment);
        $EntityName = 'InsuranceQuote';
        $ParentID = $QuoteID;

        list($attachment_content, $attachment_buttons, $css, $js) = $fc->attachmentsInterface(
            $EntityName,
            $ParentID,
            true,
            false,
            true
        );
        return view("InsuranceQuote.Backend.attachments", compact('attachment_content', 'attachment_buttons', 'css', 'js', 'title', 'QuoteID', 'Quote'));
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


    public function getCompareQuote(Request $request) 
    {

        $data = $request->all();
        $QuoteID = $data['QuoteID'];
        $html = '';

        if (is_guid($QuoteID) && $Quote = Entity::get('InsuranceQuote', $QuoteID, 3))
        {

        $Quote['currentUserId'] = Auth::user()->user_id;
        $Quote['Underwriter'] = Entity::model()->Underwriter_Get($Quote['UnderwriterID']);
        $Quote['PolicyTypeLists'] = Entity::model()->PolicyType_Get($Quote['PolicyTypeID']);
        $html = view('InsuranceQuote.Backend.modal', compact('Quote','QuoteID'))->render();
        return response()->json(['success' => true, 'data' => $html]);
        }


        return response()->json(['error' => 'Quote not found']);

    }



    public function QuoteUpdate(Request $request = null) {
        $data = $request->all();

        $quote_data = [
                'InsuranceQuoteID'    => $data['InsuranceQuoteID'],
                'UnderwriterID'       => $data['UnderwriterID'],
                'RFQID'               => $data['RFQID'],
                'CoverStartDateTime'  => $data['StartDate'],
                'CoverEndDateTime'    => $data['EndDate'],
                'EffectiveDateTime'   => $data['EffectiveDate'],
                'ExpiryDateTime'      => $data['ExpiryDate'],//Carbon::now()->format('m/d/Y H:i:s'),
                'FinalizeDateTime'    => $data['FinalizedDate'],
                'AddressID'           => $data['AddressID'],
                'Classification'      => $data['Classification'],
                'Product'             => $data['Product'],
                'Premium'             => $data['Premium'],
                'Excess'              => $data['Excess'],
                'ImposedExcess'       => $data['ImposedExcess'],
                'PolicyTypeID'        => $data['PolicyTypeID'],
                'CurrentUserID'       => $data['currentUserId']//Auth::check() ? Auth::id() : null
            ];


        $res = Entity::model()->InsuranceQuote_Update($quote_data);

        if(!$res){
            Flash::error(trans('messages.quote_failed_error'));
            return back();
        }

        Flash::success(trans('messages.quote_update_completed'));

        //return response()->json(['success' => true, 'data' => $quote_data]);
    }


}
 
