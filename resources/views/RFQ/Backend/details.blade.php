@inject('Policy', 'App\Helpers\PolicyHelper')
@extends('layouts.Backend')

@php $document->resetAssets() @endphp

@php
	$name = array_get($RFQ, "Contact", array_get($RFQ, "Lead"));
	$phone = array_get($RFQ, "Contact.MobilePhoneNumber", array_get($RFQ, "Lead.PhoneNumber"));
	$email = array_get($RFQ, "Contact.EmailAddress", array_get($RFQ, "Lead.EmailAddress"));
	$addr = array_get($RFQ, "Contact.PostalAddress", array_get($RFQ, "Lead.Address", array_get($RFQ, "InsurableBusiness.PostalAddress", [])));

	$title = implode(' ', array_filter([
		$RFQ['RFQRefNum'], 
		array_get($RFQ, "PolicyType.DisplayText"), 
		$RFQ['InsuredName'], 
		aname($name)
	]));

	$subtitle = implode(' | ', array_filter([
		address($addr),
		$phone,
		$email
	]));
	$Lead = array_has($RFQ, "Lead") ? " - Lead" : "";

	app('request')->replace(['HomeAddress' => $addr, 'MailAddress' => $addr]);

	// update form data so that Address will reflect
	Form::refresh();
@endphp

@groupblock("sub-title", "<p>{$subtitle}</p>", 'title-bottom')
@groupblock("title-aside", "<h4>".array_get($RFQ, "RFQStatus.DisplayText")."{$Lead}</h4>", "rfq-status")
@groupblock("title-aside", "RFQ.Backend.rfq-actions", "rfq-actions", compact('RFQ', 'action'))

@groupblock('sidebar-left', 'partials.rfq-gi-sidebar', 'rfq-gi-sidebar', ['active' => 'rfqs'])
@groupblock('gi-rfqs-submenu', 'RFQ.Backend.gi-sidebar', 'rfq-gi-sidebar-submenu', ['RFQID' => $RFQ['RFQID']])

@title($title)
@page_title($title)
@body_class('sidebar-mini skin-red rfq layout-full has-sidebar')


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
	<div id="form-wrapper" class="grid-100">
		{!! $html !!}
	</div>

	@include('RFQ.Backend.details-match-contact', compact('RFQ'))
	@include('RFQ.Backend.details-upload-quotes', compact('RFQ'))
	@include('RFQ.Backend.details-scripts', compact('RFQ'))
	{!! $task_content !!}
	{!! $note_content !!}
	{!! $attachment_content !!}
@endsection

<!-- additional modals -->

<div class="modal fade" id="match-client" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content box">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    		<h3 class="modal-title">Match Client</h3>
			</div>		
			<div class="modal-body">
				hello world
			</div>
	  	</div>
	</div>
</div>

<div class="modal fade" id="change-expiry-date" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content box">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    		<h3 class="modal-title">Change Expiry Date</h3>
			</div>		
			<div class="modal-body">

			{!! Form::open(['class' => 'updateExpiryDate']) !!}
				{{ Form::jInput("hidden", "RFQID", $RFQ['RFQID']) }}
				<div class="form-group" >
					{{ Form::jInput("datetime", "ExpiryDate", null, ['placeholder' => 'Expiry Date', 'class' => 'datetimepicker form-control', 'data-date-format' => 'YYYY/M/D']) }}
				</div>
   				<div class="form-group" >
					{{ Form::submit('Submit',['class' => 'btn btn-default btn-nolft-margin change_expiry_date']) }}
					
				</div>
			{!! Form::close() !!}

			</div>
	  	</div>
	</div>
</div>

<div class="modal fade" id="create-quote" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content box">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    		<h3 class="modal-title">Create Quote</h3>
			</div>		
			<div class="modal-body">
				hello world
			</div>
	  	</div>
	</div>
</div>


