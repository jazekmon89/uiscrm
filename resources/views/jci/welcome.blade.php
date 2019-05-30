@extends('jci.layouts.master-welcome-jci')

{{-- Document title not page title --}}
@title('Home')

{{-- Page title --}}
@page_title('JCI - Home')

{{-- Document/Body title --}}
@body_class('home')

@section('content')
	<div class="container">
		<div class="row">
		    <div class="bem-welcome-page__heading-container">
		    	<h4 class="bem-welcome-page__heading bem-text_center">Welcome to Just Coffee Insurance</h4>
		    </div>
	    </div>
	</div>
@endsection
<style type="text/css">
    .bem-footer {
        margin-top: 0 !important;
        position: fixed !important;
        bottom: 0;
    }
</style>