@extends('uis.layouts.master-cmi')

{{-- Document title not page title --}}
@title('Claims')

{{-- Page title --}}
@page_title('Claims')

{{-- Document/Body title --}}
@body_class('sidebar-mini skin-red')

{{-- Let Document know the css block we're trying to add --}}
@cssblock("uis.modal.spinner",'spinner-styles')
@css("plugins/jQueryUI/jquery-ui.min.css",'selectable-css')
@cssblock("uis.Claims.css.styles",'all_styles')


@push('header-css-blocks')
<style type="text/css">
	.org-policies.row a {
		height: 150px;
		background: #fff;
		border: 1px solid #eee;
		margin-bottom: 2.33333333%;
		margin-top: 2.33333333%;
	}
	.org-policies.row a input {
		visibility: hidden;
		position: absolute;
	}
</style>
@endpush

{{-- Let Document know the js block we're trying to add --}}

@js('js/app.js', 'app')

@push('nav-main-menu')
  <li class="list-group-item">{!! link_to_route('inquiries.create', "Submit an Inquiry") !!}</li>
  <li class="list-group-item">{!! link_to_route('logout', "Log-out") !!}</li>
@endpush

@section('body')

<div class="panel-body offset1">
	<div class="row">
	  	{!! $task_content !!}
	</div>
</div>
			
@include('uis.layouts.footer.dashboard')
@endsection

