@extends('layouts.master-cmi')

@title('Quote History')

@page_title('Quote History')

{{-- Document/Body title --}}
@body_class("sidebar-mini skin-red rfqs layout-box")

@section('body')
	
	<form>
		{{ Form::jInput("text", "RFQ.LodgeDateTime", null, ['readonly' => 'readonly']) }}
		{{ Form::jInput("text", "RFQ.LodgeDateTime", null, ['readonly' => 'readonly']) }}
	</form>

@endsection
