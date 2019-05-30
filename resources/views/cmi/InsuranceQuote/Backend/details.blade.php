@inject('Policy', 'App\Helpers\PolicyHelper')
@extends('layouts.Backend')

@php $document->resetAssets() @endphp

@php
	$name = array_get($Quote, "RFQ.Contact", array_get($Quote, "RFQ.Lead"));
	$phone = array_get($Quote, "RFQ.Contact.MobilePhoneNumber", array_get($Quote, "RFQ.Lead.PhoneNumber"));
	$email = array_get($Quote, "RFQ.Contact.EmailAddress", array_get($Quote, "RFQ.Lead.EmailAddress"));
	$addr = array_get($Quote, "RFQ.Contact.PostalAddress", array_get($Quote, "RFQ.Lead.Address", array_get($Quote, "RFQ.InsurableBusiness.PostalAddress", [])));

	$title = implode(' ', array_filter(['Quotes for ',
		$Quote['RFQ']['RFQRefNum'], 
		array_get($Quote, "PolicyType.DisplayText"), 
		$Quote['RFQ']['InsuredName'], 
		aname($name)
	]));

	$subtitle = implode(' | ', array_filter([
		address($addr),
		$phone,
		$email
	]));
	$Lead = array_has($Quote, "Lead") ? " - Lead" : "";

	app('request')->replace(['HomeAddress' => $addr, 'MailAddress' => $addr]);

	// update form data so that Address will reflect
	Form::refresh();
@endphp

@groupblock("sub-title", "<p>{$subtitle}</p>", 'title-bottom')
@groupblock("title-aside", "<h4>".array_get($Quote, "RFQStatus.DisplayText")."{$Lead}</h4>", "rfq-status")
@groupblock("title-aside", "InsuranceQuote.Backend.quote-actions", "quote-actions", compact('Quote', 'action'))

@groupblock('sidebar-left', 'partials.quote-gi-sidebar', 'quote-gi-sidebar', ['active' => 'quotes'])
@groupblock('gi-quotes-submenu', 'InsuranceQuote.Backend.gi-sidebar', 'quote-gi-sidebar-submenu', ['QuoteID' => $Quote['InsuranceQuoteID']])

@title($title)
@page_title($title)
@body_class('sidebar-mini skin-red quote layout-full has-sidebar')


{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')
@js('plugins/daterangepicker/moment.js', 'moment')
@js('js/datetimepicker/bootstrap-datetimepicker.js', 'bootstrap-datetimepicker', 'moment')

@jsblock('InsuranceQuote.Backend.compareQuotesHandler', 'compare-quotes-handler')

@section('content')
<style>
	.cont-wrapper .white-box.full-box{padding: 15px;}
	#search-fields .address {position: relative;}
	#search-fields th{ border: 0!important; }
		#search-fields .address .dropdown{
			position: absolute;
			top: 0;
			z-index: 100;
			width: 80%;
		}
			#search-fields .address .dropdown > a{
				display: block;
				width: 100%;
				text-decoration: none!important;
				height: 40px;
			}
			#search-fields .address .dropdown  > ul {
				width: 300px;
				padding: 5px;
			}
		 #search-fields .address .dropdown .input-group-addon {
		 	border: 0;
			background: none;
		 }
	.modal .inner-overlay{
		position: absolute;
		width: 100%;
		height: 100%;
		top: 0; left: 0;
		z-index: 100;
	}	
	#search-contact table thead tr th{
		background: #006697;
		color: #fff;
		border: 1px inset #4b8cad;
		padding: 5px!important
	}
	#search-contact table tr {border: 0 !important;}
	#search-contact table thead tr#search-fields th{
		background: #eee;
		border: 1px solid #dfdbdc !important;
		padding: 5px!important
	}
	#search-contact table tr td {
		padding: 5px!important;
	 	border: 1px solid #dfdbdc !important;
	} 
	.form-footer {display: none;}
	.rfq-form {
		-webkit-border-radius: 3px;
	    -moz-border-radius: 3px;
	    border-radius: 3px;
	}
	.rfq-form .form-tabs {
		width: 100%;
		display: inline-block!important;
		padding: 1px;
		background: #006697;
		border: 0;
		border-radius: 3px 3px 0 0;
		-moz-border-radius: 3px 3px 0 0;
		-webkit-border-radius: 3px 3px 0 0;
	}
		.rfq-form .form-tabs .form-tab-step {
			vertical-align: middle;
			float: left;
			display: inline!important;
			width: auto!important;
			height: auto!important;
		}
			.rfq-form .form-tabs .form-tab-step a {
				margin-bottom: 0!important;
				padding: 15px 10px!important;
				text-decoration: none;
				color: #fff!important;
				border: 1px solid #004768!important;
				border-left: 0!important;
				border-bottom: 0!important;
				background: #0377af;
			}
			.rfq-form .form-tabs .form-tab-step.active a{color: #000!important;}
			.rfq-form .form-tabs .form-tab-step:hover a {background: none;}
			.rfq-form .form-tabs .form-tab-step.active a,
			.rfq-form .form-tabs .form-tab-step.active:hover a {background: #fff!important;}
			.rfq-form .form-tabs .form-tab-step a:before, 
			.rfq-form .form-tabs .form-tab-step a:after {display: none;}
	.rfq-form .form-tab-contents {
		padding: 15px;
		background: #fff;
		height: 100%;
		width: 100%;
		display: table;
	}
		.rfq-form .form-tab-contents form{width: 100%!important;}
	.rfq-form .form-tabs .badge{
		background: red;
		margin-left: 5px;
		margin-top: -2px;
		color: #fff;
	}
	.rfq-form .button-group {display: none;}

	table thead tr th{
		background: #006697 !important;
		color: #fff !important;
		border: 1px inset #4b8cad !important;
	}
	table tr {
		border: 0 !important;
	}
	table thead tr#search-current-fields th, table thead tr#search-history-fields th{
		background: #eee;
		border: 1px solid #dfdbdc !important;
	}

	table tr td {
	 	border: 1px solid #dfdbdc !important;
	}

</style>
<div id="form-wrapper" class="grid-100">
	<div class="rfq-form">
	    <div class="tabs-wrapper">

	     	<ul class="form-tabs nav nav-tabs">

	     	@if(!empty($html))
				@foreach($html as $row)
					<li class="form-tab-step" id="{{ $row->QuoteRefNum }}" data-insurancequoteid="{{ $row->InsuranceQuoteID }}"><a href="#">{{ $row->QuoteRefNum }}</a></li>
				@endforeach
			@endif

	        </ul>
	    </div>
	    <div class="form-tab-contents">
	        <div class="rfq-group" id="step-one">                                           
			 	<div class="bem-container__center">
					<div class="container-fluid">
						<div class="row">
							<style>
								table thead tr th{
									background: #006697;
									color: #fff;
									border: 1px inset #4b8cad;
								}
								table tr {
									border: 0 !important;
								}
								table thead tr#search-current-fields th, table thead tr#search-history-fields th{
									background: #eee;
									border: 1px solid #dfdbdc !important;
								}

								table tr td {
								 	border: 1px solid #dfdbdc !important;
								}

								.bem-table__container {
									display: none;
								} 

							</style>
							@if(!empty($html))
								@foreach($html as $row)
								<div class="bem-table__container table-responsive" id="{{ $row->QuoteRefNum }}" data-insurancequoteid="{{ $row->InsuranceQuoteID }}">
									<table class="table table-hover table-striped rfqs-versions rfqs">
										<thead>
											<tr>
												<th>External Source Invoice No</th>
												<th>Classification</th>
												<th>Underwriter</th>
												<th>Premium</th>
												<th>Start Date</th>
												<th>End Date</th>
												<th>Effective Date</th>
												<th>...</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td id="QuoteDetails-Quote-ExternalSourceInvoiceNo">External Source Invoice No</td>
												<td id="QuoteDetails-Quote-Classification">{{ $row->Classification }}</td>
												<td id="QuoteDetails-Quote-Underwriter">Underwriter</td>
												<td id="QuoteDetails-Quote-Premium">{{ $row->Premium }}</td>
												<td id="QuoteDetails-Quote-StartDate">{{ $row->CoverStartDateTime }}</td>
												<td id="QuoteDetails-Quote-EndDate">{{ $row->ExpiryDateTime }}</td>
												<td >{{ $row->EffectiveDateTime }}</td>
												<td >

												</td>
											</tr>
										</tbody>
									</table>                            
					            </div>
				            	@endforeach
							@endif
				        </div>      
				    </div>
			    </div>
		    </div>
	    </div> 
    </div>  
</div>  
@endsection

<div id="edit_compare_quotes" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content box">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    		<h3 class="modal-title">Compare</h3>
			</div>		
			<div class="modal-body"></div>
	  	</div>
	</div>
</div>


@include('InsuranceQuote.Backend.details-upload-quotes', compact('Quote'))
@include('InsuranceQuote.Backend.details-scripts', compact('Quote'))
{!! $task_content !!}
{!! $note_content !!}
{!! $attachment_content !!}
