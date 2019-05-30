@inject('Policy', 'App\Helpers\PolicyHelper')
@extends('layouts.Backend')
@php
	$name = array_get($Quote, "Contact", array_get($Quote, "Lead"));
	$phone = array_get($Quote, "Contact.MobilePhoneNumber", array_get($Quote, "Lead.PhoneNumber"));
	$email = array_get($Quote, "Contact.EmailAddress", array_get($Quote, "Lead.EmailAddress"));
	$addr = array_get($Quote, "Contact.PostalAddress", array_get($Quote, "Lead.Address", array_get($Quote, "InsurableBusiness.PostalAddress", [])));

	$title = implode(' ', array_filter([
		$Quote['QuoteRefNum'], 
		array_get($Quote, "PolicyType.DisplayText"), 
		$Quote['RFQ']['InsuredName'], 
		aname($name),
		"- ".$title
	]));

	$subtitle = implode(' | ', array_filter([
		address($addr),
		$phone,
		$email
	]));
	$Lead = array_has($Quote, "Lead") ? " - Lead" : "";
@endphp

@title($title)
@page_title($title)
@body_class('quote layout-full has-sidebar')

@groupblock("sub-title", "<p>{$subtitle}</p>", 'title-bottom')
@groupblock('sidebar-left', 'partials.quote-gi-sidebar', 'quote-gi-sidebar', ['active' => 'quotes'])
@groupblock('gi-quotes-submenu', 'InsuranceQuote.Backend.gi-sidebar', 'quote-gi-sidebar-submenu', ['QuoteID' => $Quote['InsuranceQuoteID']])

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
				<div class="col-md-12 client_policy_notes">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Task Type</th>
								<th>Subject</th>
								<th>Description</th>
								<th>Assigned To</th>
								<th>Due Date</th>
								<th>Status</th>
								<th>Created Date</th>
							</tr>
						</thead>
						<tbody>
							@foreach($tasks as $key => $value)
								<tr>
									<td>
										{{ $value["TaskType"] }}
									</td>
									<td>
										{{ $value["Subject"] }}
									</td>
									<td>
										{{ $value["Description"] }}
									</td>
									<td>
										{{ $value["AssignedTo"] }}
									</td>
									<td>
										{{ $value["DueDateTime"] }}
									</td>
									<td>
										{{ $value["Status"] }}
									</td>
									<td>
										{{ $value["CreatedDateTime"] }}
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
				{!! $task_buttons !!}
				{!! $task_content !!}
			</div>
		</div>
	</div>
@endsection
