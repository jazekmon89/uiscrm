@extends('layouts.Backend')
@title('Insurance Quotes')
@page_title('Insurance Quotes')
@body_class("insurance-quotes layout-box")

@section('content')
	<style>
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
	@include("InsuranceQuote.Backend.list", ['quotes' => $quotes, 'type' => 'current'])

	@if($current)
		<h4>History</h4>

		@include("InsuranceQuote.Backend.list", ['quotes' => [], 'type' => 'history'])	
	@endif

@endsection
 
