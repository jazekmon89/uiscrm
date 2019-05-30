@extends('jci.layouts.Frontend')

{{-- Document title not page title --}}
@title('Submit an Inquiry')

{{-- Page title --}}
@page_title('JCI - Submit an Inquiry')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')
@js('plugins/datepicker/bootstrap-datepicker.js', 'bootstrap-datepickerjs', 'app')

@jsblock("auth.Register.registerJS.dates", "datesScript")

@section('content')
<div class="bem-form__container bem-container__center">
    <div class="container">
        <div class="row">       
            <div class="col-md-12">
                @include('flash::message')
                <div class="panel">                
                    <div class="panel-body">
                        {!! Form::open(['route' => ['inquiries.store']]) !!}
                        <div class="form-group">
                            {{ Form::label('Description', 'Please describe the assistance you require') }}
                            {{ Form::textarea('Description', null, ['class' => 'form-control', 'style' => 'min-width: 100%']) }}
                        </div>
                        <div class="form-group">                 
                            <div class="bem-inquiry-social__btn-container pull-left">              
                                @if(Auth::check())
                                    {{ Form::submit('Submit',['class' => 'btn btn-flat btn-nolft-margin']) }}           
                                @else
                                    {{ Form::submit(trans('messages.inquire_with_facebook'), ['class' => 'btn bem-inquiry-social__btn btn-facebook', 'name' => 'trigger']) }}
                                    {{ Form::submit(trans('messages.inquire_with_google'), ['class' => 'btn bem-inquiry-social__btn btn-google', 'name' => 'trigger']) }}
                                @endif 
                            </div>
                         </div>   
                        {!! Form::close() !!}
                    </div>
                </div>             
            </div>
        </div>
    </div>
</div>
<div class="v-space-7x">&nbsp;</div>
@endsection
<style type="text/css">
    .bem-page__heading-text {
        display: none;
    }
    .bem-footer {
        margin-top: 0 !important;
        position: fixed !important;
        bottom: 0;
    }
    @media (max-width: 768px) {
        .bem-member-page__heading-container {
            margin: 75px 0 15px !important;
        }
        .bem-footer {
            margin-top: 30px !important;
            position: relative !important;
            bottom: auto;
        }
    } 
</style>