@extends('layouts.master-cmi', ['ClientID'=>$ClientID])

@title('Client Profiles')

@if(!empty($Client))
	@page_title('<span style="font-weight:400;">Client Profile of</span> <b style="font-weight: 900">'. array_get($Client, "InsuredName") . '</b>')

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

@jsblock('Client.Profiles.column-handler', 'client-profile-handler')

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

		@include("Client.Profiles.preview", ['Client'=>$Client])
		@if(false)
		<div class="row">
			<div class="col-md-12 links">
				<div class="row" role="group" aria-label="Client Profiles">
					<div class="col-md-12">
					<button class="btn btn-primary" href=' #route('client.current-policies', ['CLIENTID']) ' disabled>View Current Policies</button>
					<button class="btn btn-primary" href=' #route('client.expired-policies', ['CLIENTID']) ' disabled>View Expired Policies</button>
					<button class="btn active btn-primary" href='{{ route('client.quotes', [$ClientID]) }}' >View Quotes</button>
					<button class="btn btn-primary" href=' #route('client.claims', ['CLIENTID']) ' disabled>View Claims</button>
					<button class="btn btn-primary" href=' #route('client.renewal', ['CLIENTID']) ' disabled>View Revewal / Review</button>
					</div>
				</div>
			</div>
		</div>
		@endif
	@else
	<div class="row">
		<div class="col-md-12 table-responsive profiles">
				
				<div class="col-box">	
				{{ Form::open(['route' => ["client.profiles.search"]]) }}
				<table class="table table-condensed table-hover table-striped">
					<thead>
						<tr>
							<td>Insured Name</td>
							<td>Company Name</td>
							<td>Mobile Number</td>
							<td>Email</td>
							<td>Last Update</td>
							<td>Current Policies</td>
						</tr>	
						<tr id="search-fields">
							<td>
								<div class="input-group col-md-12 search-area">
									{{ Form::text('InsuredName', null, ['placeholder' => 'Search', 'class' => 'form-control col-md-12']) }}
									<button class="btn trigger"><i class="fa fa-search"></i></button>
								</div>
							</td>
							<td>
								<div class="input-group col-md-12 search-area">
									{{ Form::text('CompanyName', null, ['placeholder' => 'Search', 'class' => 'form-control col-md-12']) }}
									<button class="btn trigger"><i class="fa fa-search"></i></button>
								</div>
							</td>
							<td>
								<div class="input-group col-md-12 search-area">
									{{ Form::text('PhoneNumber', null, ['placeholder' => 'Search', 'class' => 'form-control col-md-12']) }}
									<button class="btn trigger"><i class="fa fa-search"></i></button>
								</div>
							</td>
							<td>
								<div class="input-group col-md-12 search-area">
									{{ Form::text('EmailAddress', null, ['placeholder' => 'Search', 'class' => 'form-control col-md-12']) }}
									<button class="btn trigger"><i class="fa fa-search"></i></button>
								</div>
							</td>
							<td>
								<div class="input-group col-md-12 search-area">
									{{ Form::text('ModifiedDate', null, ['placeholder' => 'Search', 'class' => 'form-control col-md-12']) }}
									<button class="btn trigger"><i class="fa fa-search"></i></button>
								</div>
							</td>
							<td>
								<div class="input-group col-md-12 search-area">
									{{ Form::text('PolicyNum', null, ['placeholder' => 'Search', 'class' => 'form-control col-md-12']) }}
									<button class="btn trigger"><i class="fa fa-search"></i></button>
								</div>
							</td>
						</tr>	
					</thead>
				</table>
				<div class="col-md-12 text-right">
					<button type="button" class="btn btn-primary" id="reset-profiles">Reset</button>
				</div>
				<div class="spacer">&nbsp;</div>
				@php  $audit_fields = []  @endphp
				<table class="table table-condensed table-hover table-striped profiles-list" border="1">
					<thead>
						<tr>
							@foreach($client_headers as $k => $i)
								@if (preg_match('/(Created|Modified)/', $i))
									@php $audit_fields[] = $k; @endphp
								@else
									<th>{{ $i }}</th>
								@endif
							@endforeach
						</tr>
					<tbody>
						@foreach($client_profiles as $k => $i)
							<tr cid="{{ $k }}">
								@foreach($i as $h=>$j)
									@if (!in_array($h, $audit_fields))
										<td>{!! $h<5?'<a href="'.route('client.profiles', [$k]).'">'.$j.'</a>':$j !!}</td>
									@endif
								@endforeach
							</tr>
						@endforeach
					</tbody>
					
				</table>
			{{ Form::close() }}
			</div>
		</div>
	</div>
	@endif
@endsection
