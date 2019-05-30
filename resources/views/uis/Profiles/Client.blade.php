@extends('layouts.master-cmi')

{{-- Document title not page title --}}
@title('Client Profiles')

{{-- Page title --}}
@page_title('UIS CRM - Client Profiles')

{{-- Document/Body title --}}
@body_class('profiles client')

@section('body')
@include('uis.layouts.body.dashboard')
<div class="container">
    <div class="row">
        {!! Breadcrumbs::render('inquiries') !!}
        <div class="col-md-12">
            @include('flash::message')
            <div class="panel panel-default">

                <div class="panel-body">
                    {!! Form::open(['route' => ['inquiries.store']]) !!}
                    <div class="form-group" >
                        {{ Form::label('Description', 'Please describe the assistance you require.') }}
                        {{ Form::textarea('Description', null, ['class' => 'form-control', 'style' => 'min-width: 100%']) }}
                    </div>                 
                    <div class="pull-left">              
                        @if(Auth::check())
                            {{ Form::submit('Submit',['class' => 'btn btn-default btn-nolft-margin']) }}           
                        @else
                            {{ Form::submit(trans('messages.inquire_with_facebook'), ['class' => 'btn btn-primary btn-facebook btn-group-mobileleft', 'name' => 'trigger']) }}
                            {{ Form::submit(trans('messages.inquire_with_google'), ['class' => 'btn btn-danger btn-google btn-group-mobileright', 'name' => 'trigger']) }}
                        @endif 
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@include('uis.layouts.footer.dashboard')
@endsection
