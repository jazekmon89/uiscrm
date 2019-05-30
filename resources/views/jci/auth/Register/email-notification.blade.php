@extends('jci.layouts.Frontend')

{{-- Document title not page title --}}
@title('Register')

{{-- Page title --}}
@page_title('JCI - Register')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
{{--@js('js/app.js', 'appjs', 'app')--}}

@section('content') 
{!! Breadcrumbs::render('register-email-confirm') !!}
<div class="bem-form__container-md bem-page__container-white bem-form__container-rounded bem-container__center">
    <h5 class="bem-form__heading-text bem-text_center">Account Registration Complete</h5> 
    <div class="box-body">
        <section>
            <div class="wizard">
                <div class="wizard-inner">
                    <div class="connecting-line"></div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="disabled">
                            <a href="#step1">
                                <span class="round-tab">
                                    <i class="bem-step__icons glyphicon bem-step__icons glyphicon-folder-open"></i>
                                </span>
                            </a>
                        </li>
                        <li role="presentation" class="disabled">
                            <a href="#step2">
                                <span class="round-tab">
                                    <i class="bem-step__icons glyphicon bem-step__icons glyphicon-picture"></i>
                                </span>
                            </a>
                        </li>
                        <li role="presentation" class="disabled">
                            <a href="#complete">
                                <span class="round-tab">
                                    <i class="bem-step__icons glyphicon bem-step__icons glyphicon-pencil"></i>
                                </span>
                            </a>
                        </li>
                        <li role="presentation" class="active">
                            <a href="#complete">
                                <span class="round-tab">
                                    <i class="bem-step__icons glyphicon bem-step__icons glyphicon-ok"></i>
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
@endsection
