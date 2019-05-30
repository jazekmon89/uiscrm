<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Providers\Facades\Entity;
use App\Helpers\OrganisationHelper as Organisation;
use App\Helpers\PolicyHelper;
use App\Http\Requests\Quotes\RFQRequest;
use App\Http\Requests\Quotes\RFQFormDataHandler;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Flash;
use View;

class QuotesController extends Controller
{
    public function __construct(PolicyHelper $helper) {
        $this->policy = $helper;
    }

    /**
     * @param $FormTypeID
     * @param $Group selected group to get questions
     */
    public function form(RFQRequest $request, $PolicyTypeID, $FormTypeID) 
    {   
        $html = $request->generateHtml(false);
        if ($html) {
            //include policy type dispaly text
            $PolicyType = Entity::get('PolicyType', $PolicyTypeID);
            $PolicyDisplayText = array_get($PolicyType, "DisplayText");

            $OrganisationID = $request->OrganisationID();
            View::share('disptext', $PolicyDisplayText);
            return view('Quotes.RFQForm', compact('html', 'OrganisationID', 'PolicyDisplayText'));
        }

        abort(404);
    }

    // request/create form form
    public function request($OrganisationID=null) 
    {
        if (!$policies = $this->policy->getTypes($OrganisationID))
            abort(404);
        return view('Quotes.form', compact('policies', 'OrganisationID'));
    }

    public function validateForm(RFQRequest $request, $PolicyTypeID, $FormTypeID, $GroupID) {
        if ($request->validateGroup()) {
            return response()->json(["success" => true]);
        }
    }
   
    public function submit(RFQRequest $request, $PolicyTypeID, $FormTypeID) {

        $request->validateApiData($request->all());

        if ($error = $request->errors())
            return response()->json($error);
        
        $handler = (new RFQFormDataHandler($request, $this->policy))->setData($request->all());

        if ($RFQ = $handler->save()) {
            Flash::success("Your requested quote was successfully submitted. Your Reference number is: \"{$RFQ['RFQRefNum']}\"");
            return response()->json(['success' => true] + $RFQ);
        }
    
        return response()->json(['error' => 'Error saving form data', 'data' => $request->all()]);
    }

    public function index(Request $request, $OrganisationID=null) {

        $status = last(explode(".", $request->route()->getName()));
        $status = !in_array($status, ['current', 'expired']) ? 'current' : $status;

        $OrganisationID = $OrganisationID ?: Organisation::getDefaultOrganisation();
        
        if ($status == 'expired')
            $quotes = Entity::model()->Organisation_GetExpiredQuoteIDs($OrganisationID);
        else $quotes = Entity::model()->Organisation_GetCurrentQuoteIDs($OrganisationID);

        $quotes = Entity::getMultiple('InsuranceQuote', $quotes, 2);
        foreach($quotes as &$quote) {
            $quote['Notes'] = (array)Entity::model()->Note_GetNotesByParentID($quote['InsuranceQuoteID']);

            if ($quote['Notes']) {
                foreach($quote['Notes'] as &$Note) {
                    $Note->Note = Entity::get('Note', $Note->NoteID);
                    $Note->User = Entity::get('User', $Note->CreatedBy);
                }
            }

            $quote = (array)$quote;
        }
        return view('Quotes.Index.list', compact('quotes', 'status', 'OrganisationID'));
    }

    public function expireQuote(Organisation $Organisation, $OrganisationID, $InsuranceQuoteID) {
        $Quote = $this->policy->InsuranceQuote_Get_first($InsuranceQuoteID);
        
        if ($Quote) {
            $saved = $this->policy->InsuranceQuote_Update_first([
                /*'InsuranceQuoteID'    =>*/ $Quote->InsuranceQuoteID,
                /*'UnderwriterID'       =>*/ $Quote->UnderwriterID,
                /*'RFQID'               =>*/ $Quote->RFQID,
                /*'CoverStartDateTime'  =>*/ $Quote->CoverStartDateTime,
                /*'CoverEndDateTime'    =>*/ $Quote->CoverEndDateTime,
                /*'EffectiveDateTime'   =>*/ $Quote->EffectiveDateTime,
                /*'ExpiryDateTime'      =>*/ Carbon::now()->format('m/d/Y H:i:s'),
                /*'InsurancePolicyID'   =>*/ $Quote->InsurancePolicyID,
                /*'FinalizeDateTime'    =>*/ $Quote->FinalizedDateTime,
                /*'AddressID'           =>*/ $Quote->AddressID,
                /*'Classification'      =>*/ $Quote->Classification,
                /*'Product'             =>*/ $Quote->Product,
                /*'Premium'             =>*/ $Quote->Premium,
                /*'CurrentUserID'       =>*/ Auth::check() ? Auth::id() : null
            ]);

            return response()->json($saved ? ['success' => true] : $this->policy->getLastSpError());
        }
        return response()->json($this->policy->getLastSpError());
    }

    public function finalizeQuote($ClientID, $InsuranceQuoteID) {
        $Quote = $this->policy->InsuranceQuote_Get_first($InsuranceQuoteID);
        
        if ($Quote) {
            $saved = Entity::model()->InsuranceQuote_Finalize_first([
                /*'InsuranceQuoteID'    =>*/ $Quote->InsuranceQuoteID,
                /*'CurrentUserID'       =>*/ Auth::check() ? Auth::id() : null
            ], ['InsurancePolicyID' => 'uniqueidentifier']);

            return response()->json($saved ? ['success' => true] : $this->policy->getLastSpError());
        }
        return response()->json($this->policy->getLastSpError());
    }
}
 
