@inject('Policy', 'App\Helpers\PolicyHelper')
@extends('layouts.Backend')

@php $document->resetAssets() @endphp

@php
	$name = array_get($Version, "Contact", array_get($Version, "Lead"));
	$phone = array_get($Version, "Contact.MobilePhoneNumber", array_get($Version, "Lead.PhoneNumber"));
	$email = array_get($Version, "Contact.EmailAddress", array_get($Version, "Lead.EmailAddress"));
	$addr = array_get($Version, "Contact.PostalAddress", array_get($Version, "Lead.Address", array_get($Version, "InsurableBusiness.PostalAddress", [])));

	$title = implode(' ', array_filter([
		$Version['RFQRefNum'], 
		array_get($Version, "PolicyType.DisplayText"), 
		$Version['InsuredName'], 
		aname($name)
	]));

	$subtitle = implode(' | ', array_filter([
		address($addr),
		$phone,
		$email
	]));
	$Lead = array_has($Version, "Lead") ? " - Lead" : "";
@endphp

@groupblock("sub-title", "<p>{$subtitle}</p>", 'title-bottom')
@groupblock("title-aside", "<h4>".array_get($Version, "RFQStatus.DisplayText")."{$Lead}</h4>", "rfq-status")
@groupblock("title-aside", "<h4>Version ".array_get($Version, "Version")."</h4>", "rfq-version")


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
	<script>
		jQuery(document).ready(function(){
			$('.rfq-form').find('input, select, button').attr('disabled', true);
			$('.rfq-form form button.btn-submit').click(function(event) {
				event.preventDefault();
				$('.rfq-form').jsteps('next');
				return false;
			});
			$('.rfq-form').jsteps('unbindTabs');
			$('.rfq-form .tabs-wrapper .form-tabs').addClass('nav nav-tabs');
			$('.rfq-form .form-tab-step').click(function(){
				$('.rfq-form').jsteps('step', $(this).data('jindex') || 0);
			});
		});
	</script>
@endsection
