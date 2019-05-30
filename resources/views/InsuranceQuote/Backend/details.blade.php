@inject('Policy', 'App\Helpers\PolicyHelper')
@extends('layouts.Backend')

@php $document->resetAssets() @endphp

@php
	$name = array_get($Quote, "Contact", array_get($Quote, "Lead"));
	$phone = array_get($Quote, "Contact.MobilePhoneNumber", array_get($Quote, "Lead.PhoneNumber"));
	$email = array_get($Quote, "Contact.EmailAddress", array_get($Quote, "Lead.EmailAddress"));
	$addr = array_get($Quote, "Contact.PostalAddress", array_get($Quote, "Lead.Address", array_get($Quote, "InsurableBusiness.PostalAddress", [])));

	$title = implode(' ', array_filter([
		$Quote['QuoteRefNum'], 
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

</style>
	@if(false)
	<div id="form-wrapper" class="grid-100">
		{!! $html !!}
	</div>
	@endif
	{!! $task_content !!}
	{!! $note_content !!}
	{!! $attachment_content !!}
@endsection
