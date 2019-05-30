@extends('layouts.master-cmi')

@title('Search Details')

@page_title('Search Details')

{{-- Document/Body title --}}
@body_class('search')

@section('body')
	<style>
		.nav-tabs{
			background: #006697;
			border-radius: 10px 0 0 0;
			-moz-border-radius: 10px 0 0 0;
			-webkit-border-radius: 10px 0 0 0;
		}
		.nav-tabs li a{color: #fff;}
		.nav-tabs li a:hover{background: none;border: 1px solid transparent;color:#fff;}
		.nav-tabs li a .badge{margin-right:15px; }
	</style>
	<div class="row">
		<ul class="nav nav-tabs">
			<li class="{{ $method === 'FindInsuranceEntitiesByInsuranceDetails' ? 'active' : '' }}">
				<a  href="#tab1" data-toggle="tab"><span class="badge">1</span> Search Details:</a></li>
			<li class="{{ $method === 'FindContactByPersonalDetails' ? 'active' : '' }}">
				<a href="#tab2" data-toggle="tab"><span class="badge">2</span> Search Personal Details</a></li>
		</ul>
		
		<div class="tab-content">
			<br />
			<div class="tab-pane {{ $method === 'FindInsuranceEntitiesByInsuranceDetails' ? 'active' : '' }} col-md-12" id="tab1">
				@include('Search.Forms.Insurance')
			</div>
			<div class="tab-pane {{ $method === 'FindContactByPersonalDetails' ? 'active' : '' }} col-md-12" id="tab2">
				@include('Search.Forms.Personal')
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
	@if ($items)
		@php $types = [] @endphp
		@foreach($items as $key => $item)
			@php array_set($types, $item['type'] . ".$key", $item) @endphp
		@endforeach
		@foreach($types as $type => $items) 
			@include("Search.Displays.$type.list", compact('items'))
		@endforeach
	@elseif ($method)
		<p class="col-md-12 text-center">No items found.</p>
	@else
		<p class="col-md-12 text-center">...</p>
	@endif
		</div>
	</div>
@endsection