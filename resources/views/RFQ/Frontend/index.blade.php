@extends('layouts.master-cmi')

@title('Quote History')

@page_title('Quote History')

{{-- Document/Body title --}}
@body_class("sidebar-mini skin-red rfqs layout-box")

@section('body')
	
	<caption>Current Requested Quotes</caption>

	@include("RFQ.Frontend.list", ['rfqs' => $current, 'type' => 'current'])


	<caption>History of Requested Quotes</caption>

	@include("RFQ.Frontend.list", ['rfqs' => $history, 'type' => 'history'])	

@endsection
