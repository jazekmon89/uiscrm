@php

    $mail_home_owner = 'checked';
    if(!empty(old('mail_home_owner')))
        $mail_home_owner = 'checked';
    else if(isset($all_details['mail_home_owner']))
        $mail_home_owner = 'checked';
    else if(!isset($all_details['mail_home_owner']))
        $mail_home_owner = 'checked';
    else if(empty(old('mail_home_owner')))
        $mail_home_owner = '';

@endphp

@extends('layouts.master-halfbg-cmi')

{{-- Document title not page title --}}
@title('Register')

{{-- Page title --}}
@page_title('UIS CRM - Register')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}


{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')
@css('css/datepicker.min.css', 'datepicker', 'app')
@cssblock("auth.Register.css.styles", "all-styles")

{{-- Let Document know the js block we're trying to add --}}
{{--@js('js/app.js', 'appjs', 'app')--}}
@js('js/app.min.js', 'distappjs', 'app')
@js('plugins/datepicker/bootstrap-datepicker.js', 'bootstrap-datepickerjs', 'app')
@jsblock("auth.Register.registerJS.register-addresses-script", "fullAddressScript")
@jsblock("auth.Register.registerJS.dates", "datesScript")

@push('nav-main-menu')
    <li>{!! link_to_route('login', "Sign in") !!}</li>
    <li class="active"><a href="#">Register</a></li>
    <li>{!! link_to_route('inquiries.create', "Submit an Inquiry") !!}</li>
@endpush

@section('body')
<div class="container">
    <div class="row">
        <div class="registrationform-wrap wrap-center">
            <div class="box ">
                <div class="box-header with-border">
                  <h3 class="box-title">New User - Creating New Account</h3>
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
                                    <li role="presentation" class="active">
                                        <a href="#complete">
                                            <span class="round-tab">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </span>
                                        </a>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#complete">
                                            <span class="round-tab">
                                                <i class="glyphicon glyphicon-ok"></i>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane active" role="tabpanel" id="step4">
                                    @include('flash::message')
                                    <form role="form" method="POST" action="{{ route('register-address-post') }}">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="" class="control-label"></label>
                                        </div>
                                        <label for="address" class="control-label">Current Home Address:</label>
                                        <div class="form-group home">
                                            <div>
                                                <div class="checkbox">
                                                    <label><input id="home_owner" type="checkbox" class="register-checkbox" name="home_owner" value="" @if(!empty(old('home_owner'))){!!'checked'!!}@else{!! $all_details['home_owner'] or '' !!} @endif> {{ trans('messages.home_owner') }}</label>
                                                </div>
                                            </div>

                                            <label for="length_of_time" class="control-label required">Length of Time:</label>
                                            <div class="{{ $errors->has('length_of_time') ? ' has-error' : '' }}">
                                                <select id="length_of_time" class="form-control" name="length_of_time" value="" required autofocus>
                                                    <option @if(!empty(old('length_of_time'))){!! (!intval(old('length_of_time'))?'selected':'') !!}@else{!! (!intval( (array_key_exists('length_of_time', $all_details)?$all_details['length_of_time']:0))?'selected':'') !!}@endif value="0">Less than 5 years</option>
                                                    <option @if(!empty(old('length_of_time'))){!! (intval(old('length_of_time'))?'selected':'') !!}@else{!! (intval( (array_key_exists('length_of_time', $all_details)?$all_details['length_of_time']:0))?'selected':'') !!}@endif value="1">More than 5 years</option>
                                                </select>
                                                @if ($errors->has('length_of_time'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('length_of_time') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @if(false)
                                            <label for="unit_number" class="control-label">Unit Number:</label>
                                            <div class="{{ $errors->has('unit_number') ? ' has-error' : '' }}">
                                                <input id="unit_number" type="text" class="form-control" name="unit_number" value="@if(!empty(old('unit_number'))){!!old('unit_number')!!}@else{!!$all_details['unit_number'] or ''!!}@endif" autofocus>
                                                @if ($errors->has('unit_number'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('unit_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <label for="street_number" class="control-label required">Street No:</label>
                                            <div class="{{ $errors->has('street_number') ? ' has-error' : '' }}">
                                                <input id="street_number" type="text" class="form-control" name="street_number" value="@if(!empty(old('street_number'))){!!old('street_number')!!}@else{!!$all_details['street_number'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('street_number'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('street_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <label for="street_name" class="control-label required">Street Name:</label>
                                            <div class="{{ $errors->has('street_name') ? ' has-error' : '' }}">
                                                <input id="street_name" type="text" class="form-control" name="street_name" value="@if(!empty(old('street_name'))){!!old('street_name')!!}@else{!!$all_details['street_name'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('street_name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('street_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @endif
                                            <div class="form-group col-md-12">
                                                <div class="col-md-6">
                                                    <label for="address_line_1" class="control-label required">Address Line 1:</label>
                                                    <div class="{{ $errors->has('address_line_1') ? ' has-error' : '' }}">
                                                        <input id="address_line_1" type="text" class="form-control" name="address_line_1" value="@if(!empty(old('address_line_1'))){!!old('address_line_1')!!}@else{!!$all_details['address_line_1'] or ''!!}@endif" required autofocus>
                                                        @if ($errors->has('address_line_1'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('address_line_1') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="address_line_2" class="control-label required">Address Line 2:</label>
                                                    <div class="{{ $errors->has('address_line_2') ? ' has-error' : '' }}">
                                                        <input id="address_line_2" type="text" class="form-control" name="address_line_2" value="@if(!empty(old('address_line_2'))){!!old('address_line_2')!!}@else{!!$all_details['address_line_2'] or ''!!}@endif" autofocus>
                                                        @if ($errors->has('address_line_2'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('address_line_2') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <label for="town_or_suburb" class="control-label required">Town / Suburb:</label>
                                            <div class="{{ $errors->has('town_or_suburb') ? ' has-error' : '' }}">
                                                <input id="town_or_suburb" type="text" class="form-control" name="town_or_suburb" value="@if(!empty(old('town_or_suburb'))){!!old('town_or_suburb')!!}@else{!!$all_details['town_or_suburb'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('town_or_suburb'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('town_or_suburb') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <label for="post_code" class="control-label required">Post Code:</label>
                                            <div class="{{ $errors->has('post_code') ? ' has-error' : '' }}">
                                                <input id="post_code" type="text" class="form-control" name="post_code" value="@if(!empty(old('post_code'))){!!old('post_code')!!}@else{!!$all_details['post_code'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('post_code'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('post_code') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @if(false)
                                            <label for="city" class="control-label required">City:</label>
                                            <div class="{{ $errors->has('city') ? ' has-error' : '' }}">
                                                <input id="city" type="text" class="form-control" name="city" value="@if(!empty(old('city'))){!!old('city')!!}@else{!!$all_details['city'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('city'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('city') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @endif
                                            <label for="state" class="control-label required">State:</label>
                                            <div class="{{ $errors->has('state') ? ' has-error' : '' }}">
                                                {{ Form::jInput('select', 'state', $state_options, (!empty(old('state'))?old('state'):array_key_exists('state',$all_details)?$all_details['state']:''), ['class'=>'form-control', 'id'=>'state', 'required'=>true, 'autofocus'=>true]) }}
                                                @if ($errors->has('state'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('state') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <label for="birth_country" class="control-label required">Country:</label>
                                            <div class="{{ $errors->has('birth_country') ? ' has-error' : '' }}">
                                                <input id="birth_country" type="text" class="form-control" name="birth_country" value="@if(!empty(old('birth_country'))){!!old('birth_country')!!}@else{!!$all_details['birth_country'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('birth_country'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('birth_country') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="control-label"></label>
                                        </div>
                                        <label for="address" class="control-label">Mailing Address:</label>
                                        <div class="form-group">
                                            <div>
                                                <div class="checkbox">
                                                    <label><input id="mail_home_owner" type="checkbox" class="register-checkbox" name="mail_home_owner" value="" {{ $mail_home_owner }} >
                                                    {{ trans('messages.mailing_addr') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="collapse" id="mailing-address-container">
                                            @if(false)
                                            <label for="mail_unit_number" class="control-label">Unit Number:</label>
                                            <div class="{{ $errors->has('mail_unit_number') ? ' has-error' : '' }}">
                                                <input id="mail_unit_number" type="text" class="form-control" name="mail_unit_number" value="@if(!empty(old('mail_unit_number'))){!!old('mail_unit_number')!!}@else{!!$all_details['mail_unit_number'] or ''!!}@endif" autofocus>
                                                @if ($errors->has('mail_unit_number'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('mail_unit_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <label for="mail_street_number" class="control-label required">Street No:</label>
                                            <div class="{{ $errors->has('mail_street_number') ? ' has-error' : '' }}">
                                                <input id="mail_street_number" type="text" class="form-control" name="mail_street_number" value="@if(!empty(old('mail_street_number'))){!!old('mail_street_number')!!}@else{!!$all_details['mail_street_number'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('mail_street_number'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('mail_street_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <label for="mail_street_name" class="control-label required">Street Name:</label>
                                            <div class="{{ $errors->has('mail_street_name') ? ' has-error' : '' }}">
                                                <input id="mail_street_name" type="text" class="form-control" name="mail_street_name" value="@if(!empty(old('mail_street_name'))){!!old('mail_street_name')!!}@else{!!$all_details['mail_street_name'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('mail_street_name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('mail_street_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @endif
                                            <div class="form-group col-md-12">
                                                <div class="col-md-6">
                                                    <label for="mail_address_line_1" class="control-label required">Address Line 1:</label>
                                                    <div class="{{ $errors->has('mail_address_line_1') ? ' has-error' : '' }}">
                                                        <input id="mail_address_line_1" type="text" class="form-control" name="mail_address_line_1" value="@if(!empty(old('mail_address_line_1'))){!!old('mail_address_line_1')!!}@else{!!$all_details['mail_address_line_1'] or ''!!}@endif" required autofocus>
                                                        @if ($errors->has('mail_address_line_1'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('mail_address_line_1') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="mail_address_line_2" class="control-label">Address Line 2:</label>
                                                    <div class="{{ $errors->has('mail_address_line_2') ? ' has-error' : '' }}">
                                                        <input id="mail_address_line_2" type="text" class="form-control" name="mail_address_line_2" value="@if(!empty(old('mail_address_line_2'))){!!old('mail_address_line_2')!!}@else{!!$all_details['mail_address_line_2'] or ''!!}@endif" autofocus>
                                                        @if ($errors->has('mail_address_line_2'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('mail_address_line_2') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <label for="mail_town_or_suburb" class="control-label required">Town / Suburb:</label>
                                            <div class="{{ $errors->has('mail_town_or_suburb') ? ' has-error' : '' }}">
                                                <input id="mail_town_or_suburb" type="text" class="form-control" name="mail_town_or_suburb" value="@if(!empty(old('mail_town_or_suburb'))){!!old('mail_town_or_suburb')!!}@else{!!$all_details['mail_town_or_suburb'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('mail_town_or_suburb'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('mail_town_or_suburb') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <label for="mail_post_code" class="control-label required">Post Code:</label>
                                            <div class="{{ $errors->has('mail_post_code') ? ' has-error' : '' }}">
                                                <input id="mail_post_code" type="text" class="form-control" name="mail_post_code" value="@if(!empty(old('mail_post_code'))){!!old('mail_post_code')!!}@else{!!$all_details['mail_post_code'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('mail_post_code'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('mail_post_code') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @if(false)
                                            <label for="mail_city" class="control-label required">City:</label>
                                            <div class="{{ $errors->has('mail_city') ? ' has-error' : '' }}">
                                                <input id="mail_city" type="text" class="form-control" name="mail_city" value="@if(!empty(old('mail_city'))){!!old('mail_city')!!}@else{!!$all_details['mail_city'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('mail_city'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('mail_city') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @endif
                                            <label for="mail_state" class="control-label required">State:</label>
                                            <div class="{{ $errors->has('mail_state') ? ' has-error' : '' }}">
                                                <input id="mail_state" type="text" class="form-control" name="mail_state" value="@if(!empty(old('mail_state'))){!!old('mail_state')!!}@else{!!$all_details['mail_state'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('mail_state'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('mail_state') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <label for="mail_country" class="control-label required">Country:</label>
                                            <div class="{{ $errors->has('mail_country') ? ' has-error' : '' }}">
                                                <input id="mail_country" type="text" class="form-control" name="mail_country" value="@if(!empty(old('mail_country'))){!!old('mail_country')!!}@else{!!$all_details['mail_country'] or ''!!}@endif" required autofocus>
                                                @if ($errors->has('mail_country'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('mail_country') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="" class="control-label"></label>
                                        </div>
                                        <label for="address" class="prev-address-group init-hide control-label">Previous Home Address:</label>
                                        <div class="ajax-group">
                                        </div>
                                        

                                        <div class="form-group">                                       
                                            <ul class="list-inline">
                                                <li class="pull-left"><a role="button" href="{{ route('register-profile') }}" class="btn btn-primary btn-flat btn-burgundy next-step">Back</a></li>
                                                <li class="pull-right"><button type="submit" class="btn btn-primary btn-flat btn-burgundy next-step">Continue</button></li>
                                            </ul>                                       
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