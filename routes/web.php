<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Login page (as defaut page) route
Route::get('/', function(){return view('welcome');})->middleware('guest')->name('home');//return view('auth/login');})->middleware('guest');
//Route::get('/', function(){ Session::flush();return redirect(route('login'));})->middleware('guest')->name('home');

// Guest
Route::get('/login', 'Auth\LoginController@showLoginForm')->middleware('guest')->name('login');
//Route::post('/login', 'Auth\LoginController@login')->name('login-post');

//Admin
Route::get('/admin', 'Auth\LoginController@showAdminLoginForm')->middleware('cmidataaccess')->name('admin-login');
Route::post('/admin', 'Auth\LoginController@login')->name('admin-login-post');

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
//Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// OAuth Login routes
$social = array('facebook', 'google');
foreach($social as $i){
	Route::get('/'.$i.'/redirect', 'Auth\SocialAuthController@'.$i.'redirect')->name($i.'login');
	Route::get('/'.$i.'/callback', 'Auth\SocialAuthController@'.$i.'callback')->name($i.'logincallback');
	Route::get('/register/'.$i.'/redirect', 'Auth\SocialAuthController@'.$i.'regredirect')->name($i.'reg');
	Route::get('/register/'.$i.'/callback', 'Auth\SocialAuthController@'.$i.'regcallback')->name($i.'regcallback');
}


// registration with social sites OAuth
Route::get('/register/front', 'Auth\RegisterController@registerSelection')->middleware('guest')->name('register-front');

// First account confirmation page.
Route::get('/register', 'Auth\RegisterController@register')->middleware('guest')->name('register');

// For initial data validation
Route::post('/register/initial', 'Auth\RegisterController@registerInitialProcess')->middleware('guest')->name('register-post');

// For secondary data validation
Route::get('/register/secondary', 'Auth\RegisterController@registerMoreInfos')->middleware('guest'); 

// for full existing user confirmation
Route::post('/register/secondary', 'Auth\RegisterController@confirmRegistration')->middleware('guest');

// for temporary password sends
Route::get('/register/complete/temporarypass', 'Auth\RegisterController@temporaryPasswordEmailSent')->middleware('guest');

// for new user profile
Route::get('/register/profile', 'Auth\RegisterController@registerProfile')->middleware('guest')->name('register-profile');
Route::post('/register/profile', 'Auth\RegisterController@createProfile')->middleware('guest')->name('register-profile-post');

// for new user address
Route::get('/register/addresses', 'Auth\RegisterController@createAddressesPage')->middleware('guest')->name('register-address');
Route::post('/register/addresses', 'Auth\RegisterController@createFullWithAddresses')->middleware('guest')->name('register-address-post');

Route::post('/register/remove_all_prev_address', 'Auth\RegisterController@removePrevHomeAddressCount')->middleware('guest');
Route::post('/register/remove_prev_address', 'Auth\RegisterController@removeSpecificPrevHomeAddress')->middleware('guest');
Route::post('/register/add_prev_address', 'Auth\RegisterController@previousHomeAddressAddForm')->middleware('guest');

Route::post('/register/load_prev_address', 'Auth\RegisterController@displayPrevHomeAddressForm')->middleware('guest');

// Create an adviser
Route::get('/admin/register', 'Auth\RegisterController@createAdminForm')->middleware('auth')->name('admin-register');
Route::post('/admin/register', 'Auth\RegisterController@createAdmin')->middleware('auth')->name('admin-register-post');


Route::get('/email/confirmation', 'Auth\RegisterController@confirmationEmail')->middleware('guest')->name('register-email-confirm');

// home dashboard route
Route::get('/home', 'HomeController@index')->name('dashboard')->middleware('auth');

// home dashboard route
Route::get('/general-insurance/{OrganisationID?}', 'HomeController@generalInsurance')->name('general-insurance')->middleware('adminaccess');


Route::resource('inquiries', 'InquiriesController');
Route::get('inquire_with_fb', 'InquiriesController@withFB')->name('inquiries.with_facebook');
Route::get('inquire_with_google', 'InquiriesController@withGoogle')->name('inquiries.with_google');

// Attachments
Route::get('/attachments', 'FileAttachmentsController@index')->name('attachments.index');
Route::post('/attachments/upload', 'FileAttachmentsController@upload')->name('attachments.upload');
Route::post('/attachments/direct-upload', 'FileAttachmentsController@directUpload')->name('attachments.direct-upload');
Route::post('/attachments/delete', 'FileAttachmentsController@ajaxDelete')->name('attachments.delete');
Route::get('/attachments/default-files-widget/{ParentID?}', 'FileAttachmentsController@defaultExistingAttachmentsWidget')->name('attachments.default-attachments-widget');
Route::get('/attachments/update-files-widget/{ParentID?}', 'FileAttachmentsController@updateExistingAttachmentsWidget')->name('attachments.update-attachments-widget');
Route::get('/attachments/download/{FileAttachmentID}', 'FileAttachmentsController@download')->name('attachments.download');
Route::get('/attachments/getlist/{ParentID?}', 'FileAttachmentsController@defaultList')->name('attachments.default-list');
Route::get('/attachments/update-getlist/{ParentID?}', 'FileAttachmentsController@updateList')->name('attachments.update-list');
Route::post('/attachments/update', 'FileAttachmentsController@update')->name('attachments.update');


/*
|--------------------------------------------------------------------------
| Quotes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/
// Route::match(['get', 'post'], '/quotes/current/{OrganisationID?}', 'QuotesController@index')->name('quotes.index')->middleware('adminaccess');
// Route::match(['get', 'post'], '/quotes/expired/{OrganisationID?}', 'QuotesController@index')->name('quotes.index.expired')->middleware('adminaccess');
// Route::match(['get', 'post'], '/quotes/{OrganisationID}/{InsuranceQuoteID}/expire', 'QuotesController@expireQuote')->name('quotes.expire')->middleware('adminaccess');
// Route::match(['get', 'post'], '/quotes/{OrganisationID}/{InsuranceQuoteID}/finalize', 'QuotesController@finalizeQuote')->name('quotes.finalize')->middleware('adminaccess');


// no pages yet....
Route::match(['get', 'post'], '/profiles', function(){ Flash::message("Sorry, this page doesn't exist yet."); return redirect('home');})->name('profiles.index');
Route::match(['get', 'post'], '/claims', function(){ Flash::message("Sorry, this page doesn't exist yet."); return redirect('home');})->name('claims.index');
Route::match(['get', 'post'], '/renewals', function(){ Flash::message("Sorry, this page doesn't exist yet."); return redirect('home');})->name('renewals.index');
Route::match(['get', 'post'], '/lodges', function(){ Flash::message("Sorry, this page doesn't exist yet."); return redirect('home');})->name('lodges.index');
Route::match(['get', 'post'], '/actions', function(){ Flash::message("Sorry, this page doesn't exist yet."); return redirect('home');})->name('actions.index');

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/
Route::get('/insurance/search', 'InsuranceInterfaceController@searchForm');
Route::match(['get', 'post'], 'search-details/{method?}', 'SearchController@details')->name('search.details')->middleware('adminaccess');
Route::match(['get', 'post'], '/search/{method}', 'SearchController@search')->name('search')->middleware('adminaccess');

// Notes
Route::get('notes/get/{NoteID}', 'NotesController@get')->name('notes.get');
Route::get('notes/getByParent/{ParentID}', 'NotesController@getByParent')->name('notes.getbyparent');
Route::get('notes/getlist/{ParentID?}', 'NotesController@defaultList')->name('notes.default-list');
Route::get('notes/update-getlist/{ParentID?}', 'NotesController@updateList')->name('notes.update-list');
Route::post('notes/new/', 'NotesController@create')->name('notes.create');
Route::post('notes/update/', 'NotesController@update')->name('notes.update');
Route::post('notes/delete', 'NotesController@ajaxDelete')->name('notes.delete');

/*
|--------------------------------------------------------------------------
| Client Pages
|--------------------------------------------------------------------------
|
| Routes contains pages for Client, including AJAX/API
*/
Route::get('/client/profiles-search', 'ClientController@searchProfiles')->name('client.profiles.search')->middleware('adminaccess');
Route::get('/client/profiles/{ClientID?}', 'ClientController@profiles')->name('client.profiles')->middleware('adminaccess');

Route::get('/client/{ClientID}/quotes', 'ClientController@quotes')->name('client.quotes')->middleware('adminaccess');
Route::get('/client/{ClientID}/get-data/{fields?}', 'ClientController@getData')->name('client.data')->middleware('adminaccess');
Route::get('/client/{ClientID}/add-recommendation', 'ClientController@addRecommendation')->name('client.addrecommendation')->middleware('adminaccess');
Route::get('/client/{ClientID}/remove-recommendation', 'ClientController@removeRecommendation')->name('client.removerecommendation')->middleware('adminaccess');
Route::get('/client/{ClientID}/recommendations', 'ClientController@recommendations')->name('client.recommendations')->middleware('adminaccess');
Route::get('/client/{ClientID}/quote-requests', 'ClientController@history')->name('client.quote-requests');
Route::match(['get', 'post'], '/client/{ClientID}/expire_quote/{InsuranceQuoteID}', 'ClientController@expireQuote')->name('client.quotes.expire')->middleware('adminaccess');
Route::match(['get', 'post'], '/client/{ClientID}/finalize_quote/{InsuranceQuoteID}', 'ClientController@finalizeQuote')->name('client.quotes.finalize')->middleware('adminaccess');
Route::get('/client/{ClientID}/address_update/{AddressID}', 'ClientController@addressUpdate')->name('client.address-update')->middleware('adminaccess');
Route::get('/client/{ClientID}/contact_update/{ContactID}', 'ClientController@contactUpdate')->name('client.contact-update')->middleware('adminaccess');
Route::get('/client/{ClientID?}/contacts', 'ClientController@showContacts')->name('client.show-contacts')->middleware('adminaccess');
Route::get('/client/getAllProfiles/{var?}', 'ClientController@allProfilesGetRendered')->name('client.getallprofiles')->middleware('adminaccess');
Route::post('/client/{ClientID}/upload-quotes', 'ClientController@scanUploadedQuotes')->name('client.upload-quotes')->middleware('adminaccess');
Route::post('/client/{ClientID}/save-quotes', 'ClientController@saveUploadedQuotes')->name('client.save-quotes')->middleware('adminaccess');
Route::get('/client/{ClientID?}/policies', 'ClientController@clientCurrentPolicies')->name('client.policies')->middleware('adminaccess');
Route::get('/client/policy/{PolicyID}', 'ClientController@getPolicyDetails')->name('get.policy.details')->middleware('adminaccess');


/*
|--------------------------------------------------------------------------
| Tasks Pages
|--------------------------------------------------------------------------
|
| Routes contains pages for Client, including AJAX/API
*/
Route::get('/tasks/create/{EntityName?}/{ParentID?}/{TaskTypeID?}', 'TasksController@create')->name('task-form');
//Route::get('/tasks/create/form/{EntityName?}/{ParentID?}/{ClientID?}/{TaskTypeID?}', 'TasksController@create')->name('task-create-interface');
Route::post('/tasks/create', 'TasksController@store')->name('task-create');
Route::post('/tasks/update-completed', 'TasksController@completeTask')->name('task-update-completed');
Route::get('/tasks/getlist/{ParentID?}', 'TasksController@getAllDefault')->name('task-getAll');
Route::get('/tasks/update-getlist/{ParentID?}', 'TasksController@getAllWithUpdate')->name('task-getAll-update');
Route::get('/tasks/get-infos/{TaskID?}', 'TasksController@getTaskInfos')->name('task-get-infos');
//Route::get('tasks/update-form', 'TasksController@updateForm')->name('task-update-interface');
//Route::get('/tasks/files/{ParentID}', 'TasksController@getExistingFilesWidget')->name('task-files');
Route::post('/tasks/update', 'TasksController@update')->name('task-update');
Route::post('/tasks/delete', 'TasksController@ajaxDelete')->name('task-ajax-delete');

/*
|--------------------------------------------------------------------------
| Claims Pages
|--------------------------------------------------------------------------
|
| Routes contains pages for Client, including AJAX/API
*/
Route::get('/claims/request', 'ClaimsController@index')->name('claim-request');
Route::get('/claims/types', 'ClaimsController@getClaimTypes')->name('claim-types');
Route::get('/claims/create/', 'ClaimsController@create')->name('claims-create');
Route::get('/claims/inquiry/form/{InsurancePolicyID}/{ClaimTypeID}/{ClientID?}', 'ClaimsController@inquiryClaimForm')->name('claims-generic-form');
Route::post('/claims/inquiry/create/', 'ClaimsController@createInquiryClaim')->name('claims-inquiry-create');
Route::get('/claims/motorvehicle/form/{InsurancePolicyID}/{ClaimTypeID}/{OrganisationID}{ClientID?}', 'ClaimsController@motorVehicleClaimForm')->name('claims-motorvehicleloss-form');
Route::post('/claims/motorvehicle/create/', 'ClaimsController@createMotorVehicleClaim')->name('claims-motor-vehicle-create');
Route::get('/claims/history/{OrganisationID?}/{InsurancePolicyID?}', 'ClaimsController@getHistory')->name('claims-request-history');
Route::get('/claims/getpolicyselection/{OrganisationID}/{InsurancePolicyID?}/{ClientID?}', 'ClaimsController@getInsurancePoliciesRenderView')->name('claims-render-policies');

// Adviser Claims
Route::get('/admin/claims/{EntityName}/{ClientID}/{TaskTypeID?}', 'Admin\ClaimsController@index')->name('admin-claims');
Route::get('/admin/claims/get/{ClientID}', 'Admin\ClaimsController@admin_claims_get')->name('admin-claims-get');
Route::get('/admin/claims/update/{ClaimID}', 'Admin\ClaimsController@admin_claims_update')->name('admin-claims-update');
Route::get('/admin/claims/fulldetails/{ClaimID}', 'Admin\ClaimsController@admin_claims_fulldetails')->name('admin-claims-fulldetails');

// Adviser Claims - Additional
Route::get('/admin/claims/additional/{ClientID}/{ClaimID}', 'Admin\ClaimsController@admin_claims_additional')->name('admin-claims-additional');
Route::get('/admin/claims/additional/update/{ClientID}/{ClaimID}', 'Admin\ClaimsController@admin_claims_additional')->name('admin-claims-additional-update');

// Adviser Claims - History 
Route::get('/admin/claims/history/{ClientID}/{ClaimID}', 'Admin\ClaimsController@admin_claims_history')->name('admin-claims-history');
Route::get('/admin/claims/history/get/{ClientID}/{ClaimID}', 'Admin\ClaimsController@admin_claims_history_get')->name('admin-claims-history-get');
Route::get('/admin/claims/history/searchByFieldName/{ClaimID}', 'Admin\ClaimsController@admin_claims_history_searchByFieldName')->name('admin-claims-history-search');
Route::get('/admin/claims/history/current/search/{ClaimID}', 'Admin\ClaimsController@admin_claims_history_current_search')->name('admin-claims-history-current-search');
Route::get('/admin/claims/history/finalized/search/{ClaimID}', 'Admin\ClaimsController@admin_claims_current_history_search')->name('admin-claims-history-finalized-search');
Route::get('/admin/claims/history/fulldetails/{ClaimID}', 'Admin\ClaimsController@admin_claims_history_fulldetails')->name('admin-claims-history-fulldetails');


// Adviser Claims - Current claims
Route::get('/admin/claims/current/', 'Admin\ClaimsController@admin_claims_current')->name('admin-claims-current');
Route::get('/admin/claims/current/get', 'Admin\ClaimsController@admin_claims_current_get')->name('admin-claims-current-get');
Route::get('/admin/claims/current/searchByFieldName', 'Admin\ClaimsController@admin_claims_current_searchByFieldName')->name('admin-claims-current-search');
Route::get('/admin/claims/current/update/{ClaimID}', 'Admin\ClaimsController@admin_claims_current_update')->name('admin-claims-current-update');
Route::get('/admin/claims/current/fulldetails/{ClaimID}', 'Admin\ClaimsController@admin_claims_current_fulldetails')->name('admin-claims-current-fulldetails');

// Adviser Claims - Finalized claims
Route::get('/admin/claims/finalized', 'Admin\ClaimsController@admin_claims_finalized')->name('admin-claims-finalized');
Route::get('/admin/claims/finalized/get}', 'Admin\ClaimsController@admin_claims_finalized_get')->name('admin-claims-finalized-get');
Route::get('/admin/claims/finalized/searchByFieldName', 'Admin\ClaimsController@admin_claims_finalized_searchByFieldName')->name('admin-claims-finalized-search');

// Adviser Claims - Lodge claims
Route::get('/admin/claims/lodge/', 'Admin\ClaimsController@admin_claims_lodge')->name('admin-claims-lodge');
Route::get('/admin/claims/lodge/search', 'Admin\ClaimsController@admin_claims_lodge_search')->name('admin-claims-lodge-search');
Route::get('/admin/claims/lodge/searchByFieldName/{ClientID?}', 'Admin\ClaimsController@admin_claims_lodge_searchByFieldName')->name('admin-claims-lodge-search');

//Adviser Claims
Route::get('/finalized-claims', 'ClaimsController@finalizedClaimsIndex')->name('finalized-claims');
Route::get('/get-finalized-claims', 'ClaimsController@getFinalizedClaims')->name('get-finalized-claims');
Route::get('/claim-details/{ClaimID?}', 'ClaimsController@getClaimDetails')->name('claim-details');


//Policy Details
Route::get('/policy/details/', 'PolicyDetailsController@index')->name('policy-details');
Route::get('/download/coc/{coc_id?}', 'PolicyDetailsController@downloadCoC')->name('download-coc');
Route::get('/policy/amend', 'PolicyDetailsController@amend')->name('amend-policy');

//Lead
Route::get('/lead', 'LeadController@index')->name('lead');
Route::get('/lead/{rfqid?}', 'LeadController@getRFQdetails')->name('rfq-details');

/*
|--------------------------------------------------------------------------
| RFQ
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// Frontend/User RFQs
Route::get('/my-quote-requests/{RFQID?}', 'RFQController@userRFQs')->name('user.rfqs')->middleware('auth');

// backend/admin
Route::match(['get', 'post'], '/quote-requests', 'RFQController@index')->name('rfqs.index')->middleware('adminaccess');
Route::get('/quote-requests/{RFQID}/view', 'RFQController@edit')->name('rfqs.view')->middleware('adminaccess');
Route::post('/quote-requests/upload-csv-quotes', 'RFQController@uploadQuotesFromCSV')->name('rfqs.upload-csv-quotes')->middleware('adminaccess');
Route::post('/quote-requests/{RFQID}/save-quotes', 'RFQController@saveQuotes')->name('rfqs.save-quotes')->middleware('adminaccess');
Route::post('/quote-requests/{RFQID}/create-contact', 'RFQController@createContact')->name('rfqs.create-contact')->middleware('adminaccess');
Route::post('/quote-requests/{RFQID}/re-create/{ContactUserID?}', 'RFQController@reCreateRFQ')->name('rfqs.re-create')->middleware('auth');
Route::get('/quote-requests/{RFQID}/tasks', 'RFQController@tasks')->name('rfqs.tasks')->middleware('adminaccess');
Route::get('/quote-requests/{RFQID}/notes', 'RFQController@notes')->name('rfqs.notes')->middleware('adminaccess');
Route::get('/quote-requests/{RFQID}/attachments', 'RFQController@attachments')->name('rfqs.attachments')->middleware('adminaccess');
Route::get('/quote-requests/{RFQID}/versions/{VersionID?}', 'RFQController@versions')->name('rfqs.versions')->middleware('adminaccess');
Route::post('/quote-requests/{RFQID}/non_proceed', 'RFQController@nonProceed')->name('rfqs.non-proceed')->middleware('adminaccess');

// FORMS
Route::get('/quote-request/{OrganisationID?}', 'QuotesController@request')->name('quotes.request');
Route::match(['get', 'post'], '/quote-request-form/{PolicyTypeID}/{FormTypeID}', 'QuotesController@form')->name('quotes.form');
Route::match(['get', 'post'], '/quote-request-form/{PolicyTypeID}/{FormTypeID}/validate/{GroupID}', 'QuotesController@validateForm')->name('quotes.validate');
Route::match(['get', 'post'], '/quote-request-form/{PolicyTypeID}/{FormTypeID}/submit', 'QuotesController@submit')->name('quotes.submit');

// download to csv
Route::get('/csv/{RFQID}', 'FormController@index')->name('download-csv')->middleware('adminaccess');

Route::post('/quote-requests/updateExpiryDate', 'RFQController@updateExpiryDate')->name('rfqs.updateExpiryDate')->middleware('adminaccess');

/*
|--------------------------------------------------------------------------
| InsuranceQuotes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/
Route::match(['get', 'post'], '/quotes', 'InsuranceQuoteController@index')->name('insurancequotes.index')->middleware('adminaccess');
Route::get('/quotes/{QuoteID}/view', 'InsuranceQuoteController@edit')->name('insurancequotes.view')->middleware('adminaccess');
Route::get('/quotes/{QuoteID}/tasks', 'InsuranceQuoteController@tasks')->name('insurancequotes.tasks')->middleware('adminaccess');
Route::get('/quotes/{QuoteID}/notes', 'InsuranceQuoteController@notes')->name('insurancequotes.notes')->middleware('adminaccess');
Route::get('/quotes/{QuoteID}/attachments', 'InsuranceQuoteController@attachments')->name('insurancequotes.attachments')->middleware('adminaccess');
Route::post('/quotes/upload-csv-quotes', 'InsuranceQuoteController@uploadQuotesFromCSV')->name('insurancequotes.upload-csv-quotes')->middleware('adminaccess');
Route::post('/quotes/{QuoteID}/save-quotes', 'InsuranceQuoteController@saveQuotes')->name('insurancequotes.save-quotes')->middleware('adminaccess');
Route::post('/quotes/{QuoteID}/create-contact', 'InsuranceQuoteController@createContact')->name('insurancequotes.create-contact')->middleware('adminaccess');
Route::post('/quotes/{QuoteID}/re-create/{ContactUserID?}', 'InsuranceQuoteController@reCreateRFQ')->name('insurancequotes.re-create')->middleware('auth');
Route::post('/quotes/{QuoteID}/non_proceed', 'InsuranceQuoteController@nonProceed')->name('insurancequotes.non-proceed')->middleware('adminaccess');

Route::post('quotes/update/', 'InsuranceQuoteController@QuoteUpdate')->name('insurancequotes.update');


Route::post('/quotes/getCompareQuote', 'InsuranceQuoteController@getCompareQuote')->name('insurancequotes.getCompareQuote')->middleware('adminaccess');