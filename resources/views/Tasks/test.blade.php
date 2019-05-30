@extends('layouts.master-cmi')

{{-- Document title not page title --}}
@title('Tasks')

{{-- Page title --}}
@page_title('CMIData - Tasks')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the css block we're trying to add --}}
@cssblock("uis.modal.spinner",'all_styles')


{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')

@jsblock("uis.Tasks.js.scripts", "all_scripts", ['TaskTypeID'=>$task_type_id, 'ParentID' => $parent_id, 'EntityName'=>$entity_name, 'list_url'=>route('task-getAll')])

@push('nav-main-menu')
    <li class="active"><a href="#">Submit an Inquiry</a></li>
    <li>{!! link_to_route('logout', "Logout") !!}</li>
@endpush

@section('body')
<div class="container">
    <div class="row">
        {!! Breadcrumbs::render('inquiries') !!}
        <div class="col-md-12">
            @include('flash::message')
            <div class="panel panel-default">
                <div class="panel-body">
                    @include('Tasks.main')
                </div>
            </div>
        </div>
    </div>
</div>
@include('uis.layouts.footer.dashboard')
@endsection