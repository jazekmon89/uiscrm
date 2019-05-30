@extends('layouts.Backend')

@title('Client Profiles')

@if(!empty($Client))
	
	@php
		$name = $subtitle = "";
		if($Contact = array_get($Client, "Contacts.0")) 
		{
			$name = aname($Contact);
			$subtitle .= "<div>";
			
			if($Address = array_get($Contact, "HomeAddress")) 
				$subtitle .= "<span class=\"address\">".address($Address)."</span>";
			if($phone = array_get($Contact, "MobilePhoneNumber"))
				$subtitle .= "<span class=\"separator\"> - </span><span class=\"mobile\">". $phone ."</span>";
			
			if($email = array_get($Contact, "EmailAddress"))
				$subtitle .= "<span class=\"separator\"> - </span><span class=\"email\">". $email ."</span>";
			if($ABN = array_get($Client, "ABN"))
				$subtitle .= "<span class=\"separator\"> - </span><span class=\"email\">". $ABN ."</span>";
			
			$subtitle .= "</div>";
		}
		if ($subtitle)
			$document->addblock('sub-title', $subtitle, 'title-bottom');			
	@endphp

	@page_title(array_get($Client, "InsuredName"). '-'. $name)
	@groupblock('sidebar-left', 'partials.gi-sidebar', 'gi-sidebar', ['active' => 'client.profiles'])
	@groupblock('gi-client-profile-submenu', 'Client.gi-sidebar', 'client-gi-sidebar', compact('ClientID'))
	@body_class('has-sidebar')
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

@section('content')
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
			top: 0;
    		height: 100%;
		}
		.profiles-list {
			display: block;
    		overflow-x: scroll;
		}
		table.profiles-list{
			border-collapse: collapse;
		}
		.profiles-list th, .profiles-list td {
			white-space:nowrap;
			line-height: 24px;
			vertical-align: middle !important;
		}
		.profiles-list td {
			height: 33px;
		}
		.profiles-list td {
			border: 1px solid #dfdbdc !important;
		}
		.profiles-list th {
		    height: 50px;
		    border: 1px solid #4b8cad !important;
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
	<div class="bem-container__center">
		<div class="container-fluid">
			<div class="row">
				<div class="bem-table__container table-responsive">				
					{{ Form::open(['route' => ["client.profiles.search"]]) }}
					<table class="table table-hover table-striped table-fixed-header">
						<thead>
							<tr>
								<td>Insured Name</td>
								<td>Trading Name</td>
								<td>Mobile Number</td>
								<td>Email</td>
								<td>Last Update</td>
								<td>Current Policies</td>
							</tr>	
							<tr id="search-fields">
								<td>
									<div class="bem-table__search-container input-group search-area">
										{{ Form::text('InsuredName', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
										<button class="btn trigger"><i class="fa fa-search"></i></button>
									</div>
								</td>
								<td>
									<div class="bem-table__search-container input-group search-area">
										{{ Form::text('TradingName', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
										<button class="btn trigger"><i class="fa fa-search"></i></button>
									</div>
								</td>
								<td>
									<div class="bem-table__search-container input-group search-area">
										{{ Form::text('PhoneNumber', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
										<button class="btn trigger"><i class="fa fa-search"></i></button>
									</div>
								</td>
								<td>
									<div class="bem-table__search-container input-group search-area">
										{{ Form::text('EmailAddress', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
										<button class="btn trigger"><i class="fa fa-search"></i></button>
									</div>
								</td>
								<td>
									<div class="bem-table__search-container input-group search-area">
										{{ Form::text('ModifiedDate', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
										<button class="btn trigger"><i class="fa fa-search"></i></button>
									</div>
								</td>
								<td>
									<div class="bem-table__search-container input-group search-area">
										{{ Form::text('PolicyNum', null, ['placeholder' => 'Search', 'class' => 'form-control bem-table__search-input']) }}
										<button class="btn trigger"><i class="fa fa-search"></i></button>
									</div>
								</td>
							</tr>	
						</thead>
					</table>
					<div class="col-md-12 bem-text_right">
						<button type="button" class="btn btn-primary" id="reset-profiles">Reset</button>
					</div>
					<div class="spacer">&nbsp;</div>
					@php  $audit_fields = []  @endphp
					<table class="table table-condensed table-hover table-striped profiles-list" >
						<thead>
							<tr>  
								@foreach($client_headers as $k => $i)
									@php
										if(preg_match('/(Australian Business Number)/i', $i))
										{
											$i = "ABN";
										}
										elseif(preg_match('/(G S T)$/i', $i))
										{
											$i = "Is Registered For GST";
										}
									@endphp
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