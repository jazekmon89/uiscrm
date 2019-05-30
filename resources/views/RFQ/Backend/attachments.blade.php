@inject('Policy', 'App\Helpers\PolicyHelper')
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
		aname($name),
		"- Attachments"
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
@body_class('rfq layout-full has-sidebar')

@groupblock("sub-title", "<p>{$subtitle}</p>", 'title-bottom')
@groupblock('sidebar-left', 'partials.rfq-gi-sidebar', 'rfq-gi-sidebar', ['active' => 'rfqs'])
@groupblock('gi-rfqs-submenu', 'RFQ.Backend.gi-sidebar', 'rfq-gi-sidebar-submenu', ['RFQID' => $RFQ['RFQID']])

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')
@js('plugins/daterangepicker/moment.js', 'moment')
@js('js/datetimepicker/bootstrap-datetimepicker.js', 'bootstrap-datetimepicker', 'moment')

@section('content')
	<style>
	.form-tab-step a{
		color: #636b6f !important;
	}

	.form-tab-step.active a{
		color: #460000 !important;
	}
	.cont-wrapper .white-box.full-box{
		padding: 15px;
	}
	#search-fields .address {
		position: relative;
	}
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
@if(false)
<!--div id="form-wrapper" class="grid-100">
	<div class="row">

			{!! $file_content !!}
		
		<div class="col-md-12 client_policy_notes">
			<div class = "form-group">
				<button type="button" class="btn btn-default" data-toggle="modal" data-target="#new_file">
				  Add Attachment
				</button>
			</div>

			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Title</th>
						<th>Created By</th>
						<th>Date Created</th>
						<th class = "actions">Action</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach($attachments as $key => $value)
						<tr class = "attachments-list">
							<td>
								{{ $value["Title"] }}
							</td>
							<td>
								{{ $value["CreatedBy"] }}
							</td>
							<td>
								{{ $value["CreatedDateTime"] }}
							</td>
							<td>
								<a href = "{{ route('attachments.delete', $value['FileAttachmentID']) }}"><span class="glyphicon glyphicon-remove-circle"></span></a>
							</td>
						</tr>
					@endforeach
				
				</tbody>
			</table>
		</div>
	</div>
</div-->
@endif
	
	<div id="form-wrapper" class="grid-100">
		<div class="row">
			<div class="col-md-12 client_policy_notes">
				{!! $attachment_buttons !!}
				{!! $attachment_content !!}
			</div>
		</div>
	</div>

@endsection
