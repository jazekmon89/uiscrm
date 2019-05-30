@extends('layouts.master-cmi', ['ClientID'=>$ClientID])

@title('Client Contacts')

@if( !empty($contacts))

	@php
		
		if($Contact = array_get($Client, "Client.0")) {
			$address = '';
			$phone_email_abn = '';
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
				$address .= implode(' ', array_filter($addr));
			}
			if($phone = array_get($Contact, "MobilePhoneNumber"))
				$phone_email_abn .= '<span class="separator"> - </span><span class="mobile">'. $phone .'</span>';
			if($email = array_get($Contact, "EmailAddress"))
				$phone_email_abn .= '<span class="separator"> - </span><span class="email">'. $email .'</span>';
			if(isset($Client['ABN']) && !empty($Client['ABN']))
				$phone_email_abn .= '<span class="separator"> - </span><span class="abn">'. $Client['ABN'] .'</span>';
	@endphp
			@page_title('<span style="font-weight:400;">'. $Client['InsuredName'] . ' - '. implode(' ', [array_get($Contact, 'FirstName'), array_get($Contact, 'Surname')]).'<div style="display:inline-block;font-size:13px;font-weight:normal;color:#636b6f;">'.$address.$phone_email_abn.'</div></span></h4>')
	@php
		}	
	@endphp

	@if( false)
		@groupblock('title-bottom', $subtitle, 'subtitle')
	@endif

@endif



{{-- Document/Body title --}}
@body_class('client-profiles layout-box')

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
		.contacts-list {
    		overflow-x: scroll;
		}
		.contacts-list th, .contacts-list td {
			white-space:nowrap;
			line-height: 24px;
		}
		.contacts-list th {
		    height: 50px;
		    vertical-align: middle !important;
		}
		.contacts-list thead {
			background: #006697;
			color: #fff;
		}
		.contacts-list tbody tr:hover {
			cursor: pointer;
		}
	</style>

	<div class="row">
		<div class="col-md-12 table-responsive profiles">
			<div class="col-box">
				<table class="table table-condensed table-hover table-striped contacts-list" border="1">
					<thead>
						<tr>
							<th>Contact Ref No</th>
							<th>First Name</th>
							<th>Preffered Name</th>
							<th>Surname</th>
							<th>Address</th>
							<th>Mobile</th>
							<th>Email</th>
						</tr>
					</thead>
					<tbody>
						@foreach($contacts as $a => $b)
							<tr cid='{{ $ClientID }}'>
								<td>
									<a href="#">{{ $b["ContactRefNum"] }}</a>
								</td>
								<td>
									<a href="#">{{ $b["FirstName"] }}</a>
								</td>
								<td>
									<a href="#">{{ $b["PreferredName"] }}</a>
								</td>
								<td>
									<a href="#">{{ $b["Surname"] }}</a>
								</td>
								<td>
									{{ $b["HomeAddress"] }}						
								</td>
								<td>
									{{ $b["MobilePhoneNumber"] }}						
								</td>
								<td>
									{{ $b["EmailAddress"] }}						
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			{{ Form::close() }}
			</div>
		</div>
	</div>
	
@endsection
