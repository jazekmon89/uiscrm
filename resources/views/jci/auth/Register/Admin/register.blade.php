@extends('jci.layouts.master-welcome-jci')

{{-- Document title not page title --}}
@title('Register')

{{-- Page title --}}
@page_title('UIS CRM - Register')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

@push('nav-main-menu')
    <li>{!! link_to_route('login', "Sign in") !!}</li>
    <li class="active"><a href="#">Register</a></li>
    <li>{!! link_to_route('inquiries.create', "Submit an Inquiry") !!}</li>
@endpush

@section('body')
    <div class="row">
        {!! Breadcrumbs::render('admin-register') !!}
        <div class="registrationform-wrap wrap-center">
            <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Adviser Registration</h3>
                </div>
                <div class="box-body">
                    <section>
                        <div class="wizard">
                            <div class="tab-content">
                                <div class="tab-pane active" role="tabpanel" id="step1">
                                    {!! Form::open(['route' => ['admin-register-post']]) !!}
                                        @include('flash::message')
                                        @if($errors)
                                            @php Form::ErrorBag($errors->getMessageBag()); @endphp
                                        @endif
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            {{ Form::label('Description', 'First name') }}
                                            {{ Form::jInput('text', "first_name", null, ['class' => 'form-control', 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('Description', 'Surname') }}
                                            {{ Form::jInput('text', "surname", null, ['class' => 'form-control', 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('Description', 'Username') }}
                                            {{ Form::jInput('text', "username", null, ['class' => 'form-control', 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('Description', 'Email Address') }}
                                            {{ Form::jInput('text', "email_address", null, ['class' => 'form-control', 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('Description', 'Password: ') }}
                                            {{ Form::jInput('password', "password", null, ['class' => 'form-control', 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('Description', 'Confirm password: ') }}
                                            {{ Form::jInput('password', "password_confirmation", null, ['class' => 'form-control', 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('Description', 'Organization ID') }}
                                            {{ Form::jInput('text', "organisation_id", null, ['class' => 'form-control', 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('Description', 'Organization Role name') }}
                                            {{ Form::jInput('text', "organisation_role_name", null, ['class' => 'form-control', 'required']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('Description', 'Adviser License Number') }}
                                            {{ Form::jInput('text', "adviser_license_number", null, ['class' => 'form-control']) }}
                                        </div>
                                        <div class="form-group">                                       
                                            <ul class="list-inline">
                                                <li class="pull-right"><button type="submit" class="btn btn-primary btn-flat btn-burgundy next-step">Continue</button></li>
                                            </ul>                                       
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                                <div class="clearfix"></div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </section>
                </div>
            </div> 
        </div>                   
    </div>
@include('uis.layouts.footer.dashboard')
@endsection
