@extends('layouts.Backend')
@title('Insurance Quotes')
@page_title('Insurance Quotes')
@body_class("insurance-quotes layout-box")

@section('content')
	@include("InsuranceQuote.Backend.list", ['quotes' => [], 'type' => 'current'])

	@if($current)
		<h4>History</h4>

		@include("InsuranceQuote.Backend.list", ['quotes' => [], 'type' => 'history'])	
	@endif

@endsection
 
