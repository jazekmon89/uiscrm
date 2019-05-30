@extends('layouts.Backend')
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
@endphp

@title($title)
@page_title($title)

@groupblock("sub-title", "<p>{$subtitle}</p>", 'title-bottom')
@groupblock("title-aside", "<h4>".array_get($RFQ, "RFQStatus.DisplayText")."{$Lead}</h4>", "rfq-status")
@groupblock('sidebar-left', 'partials.rfq-gi-sidebar', 'rfq-gi-sidebar', ['active' => 'rfqs'])
@groupblock('gi-rfqs-submenu', 'RFQ.Backend.gi-sidebar', 'rfq-gi-sidebar-submenu', ['RFQID' => $RFQ['RFQID']])
@body_class("sidebar-mini skin-red rfqs layout-box has-sidebar")

@section('content')
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
	</style>
	<table class="table table-condensed table-hover table-striped rfqs-versions rfqs">
	<thead>
		<tr>
			<th>Version</th>
			<th>RFQ Type</th>
			<th>RFQ Status</th>
			<th>Insured Name</th>
			<th>Name</th>
			<th>Date Submitted</th>
		</tr>
	</thead>
	<tbody>
		@if($versions)
		@foreach($versions as $version)
			@php $link = route("rfqs.versions", [$RFQID, $version->ID]) @endphp
			<tr>
				<td>@iLink($link, data_get($version, "Version"))</td>
				<td>@iLink($link, data_get($version, "PolicyType"))</td>
				<td>@iLink($link, data_get($version, "RFQStatus"))</td>
				<td>@iLink($link, data_get($version, "InsuredName"))</td>
				<td>@iLink($link, data_get($version, "Name"))</td>
				<td>@dateFormat(data_get($version, "LodgementDateTime"))</td>
			</tr>
		@endforeach
		@else
			<tr><td colspan="6">No Versions</td></tr>
		@endif
	</tbody>
	</table>	
@endsection
