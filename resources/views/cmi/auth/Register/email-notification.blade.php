@extends('layouts.master-cmi')

{{-- Document title not page title --}}
@title('Register')

{{-- Page title --}}
@page_title('UIS CRM - Register')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
{{--@js('js/app.js', 'appjs', 'app')--}}
@js('js/uis.js', 'uisjs', 'app')
@js('js/app.min.js', 'distappjs', 'app')

@push('nav-main-menu')
    <li>{!! link_to_route('login', "Sign in") !!}</li>
    <li class="active"><a href="#">Register</a></li>
    <li>{!! link_to_route('inquiries.create', "Submit an Inquiry") !!}</li>
@endpush

@section('body')
@include('uis.layouts.body.guest')
<div class="container">
    <div class="row">
        {!! Breadcrumbs::render('register-email-confirm') !!}
        <div class="registrationform-wrap wrap-center">
            <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Account registration complete</h3>
                </div>
                <div class="box-body">
                    <section>
                        <div class="wizard">
                            <div class="wizard-inner">
                                <div class="connecting-line"></div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="disabled">
                                        <a href="#step1">
                                            <span class="round-tab">
                                                <i class="glyphicon glyphicon-folder-open"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#step2">
                                            <span class="round-tab">
                                                <i class="glyphicon glyphicon-picture"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#complete">
                                            <span class="round-tab">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="active">
                                        <a href="#complete">
                                            <span class="round-tab">
                                                <i class="glyphicon glyphicon-ok"></i>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane active" role="tabpanel" id="complete">
                                    @include('flash::message')
                                    <form class="form-horizontal" role="form" method="GET" action="{{ route('login') }}">
                                        {{ csrf_field() }}

                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                                            <div class="col-md-12">
                                                Account has been successfully created. You may now login.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-8 col-md-offset-4">
                                                <button type="submit" class="btn btn-primary">
                                                    Go to Login
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
