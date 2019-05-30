@extends('layouts.master-halfbg-cmi')

{{-- Document title not page title --}}
@title('Submit an Inquiry')

{{-- Page title --}}
@page_title('UIS CRM - Submit an Inquiry')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}

{{--@js('js/app.js', 'appjs', 'app')--}}
@js('js/uis.js', 'uisjs', 'app')
@js('js/app.min.js', 'distappjs', 'app')
@js('plugins/datepicker/bootstrap-datepicker.js', 'bootstrap-datepickerjs', 'app')

@jsblock("auth.Register.registerJS.dates", "datesScript")


@section('body')
<div class="container">  
   <p class="text-right inquiry-text-links">
        <a href="{{ route("quotes.request") }}" class="">Obtain a Quote</a> | 
        <a href="{{ route("login") }}" class="">Sign In</a> | 
        <a href="{{ route("register-front") }}" class="">Register</a>
    </p>    
    <div class="row form-center">       
        <div class="col-md-12">
            @include('flash::message')
            <div class="panel cstm-default-box-body">                
                <div class="panel-body">
                    {!! Form::open(['route' => ['inquiries.store']]) !!}
                    <div class="form-group" >
                        {{ Form::label('Description', 'Please describe the assistance you require') }}
                        {{ Form::textarea('Description', null, ['class' => 'form-control', 'style' => 'min-width: 100%']) }}
                    </div>                 
                    <div class="pull-left">              
                        @if(Auth::check())
                            {{ Form::submit('Submit',['class' => 'btn btn-flat btn-nolft-margin']) }}           
                        @else
                            {{ Form::submit(trans('messages.inquire_with_facebook'), ['class' => 'btn btn-inq-social btn-facebook btn-flat btn-group-mobileleft', 'name' => 'trigger']) }}
                            {{ Form::submit(trans('messages.inquire_with_google'), ['class' => 'btn btn-inq-social btn-google btn-flat btn-group-mobileright', 'name' => 'trigger']) }}
                        @endif 
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>             
        </div>
    </div>
</div>
@endsection
