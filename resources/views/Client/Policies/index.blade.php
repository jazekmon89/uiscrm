@extends('uis.layouts.master-cmi', ['ClientID'=>$ClientID])

@title('Client Profiles')

@if(!empty($Client))
	@page_title('<span style="font-weight:400;">Policy list of</span> <b style="font-weight: 900">'. array_get($Client, "ContactPersion") . '</b>')

	@if(false)
	@toolbar('title-toolbars', 'back-button', "<a class='btn btn-primary navbar-btn' href='".route("client.profiles")."'>Back</a>")
	@endif
	@php
		$subtitle = "";
		if($Contact = array_get($Client, "Contacts.0")) {
				
				$subtitle .= "<div>";
				$subtitle .= "<span class=\"name\">". implode(" ", [array_get($Contact, "FirstName"), array_get($Contact, "Surname")]) ."</span>";
				
				if($phone = array_get($Contact, "MobilePhoneNumber"))
					$subtitle .= "<span class=\"separator\"> - </span><span class=\"mobile\">". $phone ."</span>";
				
				if($email = array_get($Contact, "EmailAddress"))
					$subtitle .= "<span class=\"separator\"> - </span><span class=\"email\">". $email ."</span>";
				
				if($Address = array_get($Contact, "HomeAddress")) {
					$addr = [
						//array_get($Address, "UnitNumber"),
						//array_get($Address, "StreetNumber"),
						//array_get($Address, "StreetName"),
						array_get($Address, "AddressLine1"),
						array_get($Address, "AddressLine2")
						array_get($Address, "City"),
						array_get($Address, "State"),
						array_get($Address, "Country"),
						array_get($Address, "Postcode")
					];
					$subtitle .= "<p class=\"address\">". implode(" ", array_filter($addr)) ."</p>";
				}
				$subtitle .= "</div>";
		}	
	@endphp

	@if($subtitle)
		@groupblock('title-bottom', $subtitle, 'subtitle')
	@endif
	
@else
	@page_title('Clients')
@endif

{{-- Document/Body title --}}
@if (!empty($Client))
	@body_class('client-profiles layout-box')
@else
	@body_class('client-profiles layout-full no-main-sidebar')
@endif

{{-- Let Document know the css block we're trying to add --}}
@css("/plugins/datepicker/datepicker3.css", "datepicker", 'app')

@js("/plugins/datepicker/bootstrap-datepicker.js", "datepicker", "app")

@jsblock('Client.Policies.js', 'js')

@section('body')
	<style>
		#page-title .toolbars {margin: 20px 0;}
			#page-title .toolbars a{
				padding: 20px;
				color: #fff;
				margin: 0;
			}
			#page-title .toolbars a:hover {
				color: #fff;
    			background-color: #2579a9;
    			border-color: #1f648b;
			}
		#page-title .title h5{margin: 0;}
		#search-fields td{position: relative;padding: 5px 0;}
		#search-fields td .btn-group{padding: 0;}
		#search-fields td input{text-align: left;}
		#search-fields .btn.trigger{
			position: absolute;
			right: 0;
			z-index: 100;
		}
		.profiles-list {
			display: block;
    		overflow-x: scroll;
		}
		.profiles-list th, .profiles-list td {
			white-space:nowrap;
			line-height: 24px;
			vertical-align: middle !important;
		}
		.profiles-list td {
			height: 33px;
		}
		.profiles-list th {
		    height: 50px;
		}
		.profiles-list thead {
			background: #006697;
			color: #fff;
		}
		/*.profiles-list tbody tr:hover {
			cursor: pointer;
		}*/
		.profiles-list tbody td a {
			text-decoration: underline;
		}
	</style>

	@if(!empty($Client))

	<div class="row">
		<div class="col-md-12 panel-body offset1">
			<div class="row">
				<div class="col-md-6">
					<h3><span class="InsuredName">Insured Name</span> - Current Policies</h3>
				</div>
				<div class="col-md-6 form-inline text-right">
					{{ Form::jLabel("Search", 'Search:') }}
					{{ Form::jInput("text", "Search", null, ['class' => 'form-control']) }}
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("ReferenceNo", 'Reference No.:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "ReferenceNo", null, ['class' => 'form-control', 'readonly'=>'readonly']) }}
						</div>
					</div>
				</div>
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("Classification", 'Classification:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "Classification", null, ['class' => 'form-control', 'placeholder' => 'Motor Vehicle, Home, etc.', 'readonly'=>'readonly']) }}
						</div>
					</div>
				</div>
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("CoverStartDate", 'Cover Start Date:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "CoverStartDate", null, ['class' => 'form-control', 'readonly'=>'readonly']) }}
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("PolicyNo", 'Policy No.:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "PolicyNo", null, ['class' => 'form-control', 'readonly'=>'readonly']) }}
						</div>
					</div>
				</div>
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("Underwriter", 'Underwriter:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "Underwriter", null, ['class' => 'form-control', 'placeholder' => 'CGU, QBE, etc.', 'readonly'=>'readonly']) }}
						</div>
					</div>
				</div>
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("CoverEndDate", 'Cover End Date:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "CoverEndDate", null, ['class' => 'form-control', 'readonly'=>'readonly']) }}
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("QuoteNo", 'Quote No.:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "QuoteNo", null, ['class' => 'form-control', 'readonly'=>'readonly']) }}
						</div>
					</div>
				</div>
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("Product", 'Product:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "Product", null, ['class' => 'form-control', 'placeholder' => 'Comprehensive, Landlord...', 'readonly'=>'readonly']) }}
						</div>
					</div>
				</div>
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("Balance", 'Balance:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "Balance", null, ['class' => 'form-control', 'placeholder' => 'Paid / Outstanding']) }}
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("InsuredName", 'Insured Name:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "InsuredName", null, ['class' => 'form-control', 'readonly'=>'readonly']) }}
						</div>
					</div>
				</div>
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("Premium", 'Premium:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "Premium", null, ['class' => 'form-control', 'readonly'=>'readonly']) }}
						</div>
					</div>
				</div>
				<div class="form-group col-md-4">
					<div class="form-group">
						{{ Form::jLabel("InvoiceNo", 'Invoice No.:', ['class' => 'col-sm-3']) }}
						<div class="col-sm-9">
							{{ Form::jInput("text", "InvoiceNo", null, ['class' => 'form-control']) }}
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-12">
					<div class="form-group">
						{{ Form::jLabel("Address", 'Address:', ['class' => 'col-sm-1']) }}
						<div class="col-sm-11">
							{{ Form::jInput("textarea", "Address", null, ['class' => 'form-control', 'rows' => 3, 'readonly'=>'readonly']) }}
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12 text-right">
					<button type="button" class="btn btn-default" id="newPolicy">New Policy</button>
					<button type="button" class="btn btn-default" id="lodgeClaim">Lodge Claim</button>
					<button type="button" class="btn btn-default" id="fullDetails">Full Details</button>
					<button type="button" class="btn btn-default" id="startRenewal">Start Renewal</button>

					@if($task_buttons)
		            {!! $task_buttons !!}
		            @endif

				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<h3>Current Tasks</h3>
					@if($task_content)
	                {!! $task_content !!}
	                @endif
				</div> 
			</div>
			<div class="row">
				<div class="col-md-12 current_policies">
					<h3>Current Policies</h3>
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>Date Submitted</th>
								<th>Policy No</th>
								<th>Insured Name</th>
								<th>Cover Start Date</th>
								<th>Cover End Date</th>
								<th>Underwriter</th>
								<th>Product</th>
								<th>Claim History</th>
							</tr>
						</thead>
						@foreach($policy_details as $key => $value)
						<tr class = "client-policies-list" data-id = "{{$value["details"]['InsurancePolicyID']}}">
								<td>{{ $value["details"]["CreatedDateTime"] }}</td>
								<td>{{ $value["details"]["PolicyNum"] }} </td>
								<td>{{ $value["details"]["Client"]["InsuredName"] }} </td>
								<td>{{ $value["details"]["CoverStartDateTime"] }} </td>
								<td>{{ $value["details"]["CoverEndDateTime"] }} </td>
								<td>{{ $value["details"]["Underwriter"]["CompanyName"] }} </td>
								<td>{{ $value["details"]["Product"] }} </td>
								<td>-  </td>
						</tr>
					@endforeach
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 client_policy_notes">
					<h3>Notes</h3>
					{!! $note_content !!}
					<!--table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Date Created</th>
								<th>Created by</th>
								<th>Description</th>
							</tr>
						</thead>
					</table-->
				</div>
				<div class="col-md-6 client_policy_attachments">
					<h3>Attachment</h3>
					{!! $attachment_content !!}
					<!--table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Title</th>
								<th>Date Modified</th>
								<th>Comments</th>
							</tr>
						</thead>
					</table-->
				</div>
			</div>
		</div>
	</div>
		
	@endif
@endsection
	