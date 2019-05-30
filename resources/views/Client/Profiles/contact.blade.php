@extends('layouts.Backend')
@title('Client Contacts')
@php
	$name = $subtitle = "";
	
	if($Contact = head($contacts)) 
	{
		$name = aname($Contact);
		$subtitle .= "<div>";
		
		if($Address = array_get($Contact, "HomeAddress")) 
			$subtitle .= "<span class=\"address\">". $Address ."</span>";

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


{{-- Document/Body title --}}
@body_class('client-profiles layout-box')

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
		}
		.contacts-list {
    		overflow-x: scroll;
		}
		.contacts-list th, .contacts-list td {
			white-space:nowrap;
			line-height: 24px;
			border: 1px solid #dfdbdc !important;
		}
		.contacts-list th {
		    height: 50px;
		    vertical-align: middle !important;
		    border: 1px solid #4b8cad !important;
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
