<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SearchHelper;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
	public function __construct() {}

	/**
	 * @return view search details page
	 */
    public function details(SearchHelper $helper, Request $request, $method=null) { 	
        if ($method) {
            $items = $helper->search($method);
        }
        else {
            $items = null;

            if ($search = $request->Input("Search")) {

                $text = array_get($search, "Input", "");
                $field = array_get($search, "Field", "FindInsuranceEntitiesByInsuranceDetails.InsuredName");

                if ($text && $field) {
                    list($method, $field) = explode(".", $field);

                    $request->merge([$field => $text]);
                    
                    $items = $helper->search($method);
                }
            }
        }
        
        $method = $method ?: 'FindInsuranceEntitiesByInsuranceDetails';

    	return view('Search.Details', compact('items', 'method'));
    }

    /**
     * @param api method
     */
    public function search(SearchHelper $helper, $method) {
    	return response()->json((array)$helper->search($method));
    }
}
