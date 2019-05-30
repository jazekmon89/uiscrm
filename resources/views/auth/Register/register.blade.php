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

{{-- Let Document know the js block we're trying to add --}}
{{--@js('js/app.js', 'appjs', 'app')--}}
@js('js/uis.js', 'uisjs', 'app')
@js('js/app.min.js', 'distappjs', 'app')
@js('plugins/datepicker/bootstrap-datepicker.js', 'bootstrap-datepickerjs', 'app')

@jsblock("auth.Register.registerJS.dates", "datesScript")


@section('body')
    <div class="row">
        <div class="registrationform-wrap wrap-center">
            <div class="box cstm-default-box-body">
                <div class="box-header with-border">
                  <h5 class="text-center">New User - Creating New Account</h5>
                </div>
                <div class="box-body">
                    <section>
                        <div class="wizard">
                            <div class="wizard-inner">
                                <div class="connecting-line"></div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active">
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
                                <div class="tab-pane active" role="tabpanel" id="step1">
                                    @include('flash::message')
                                    <h5 class="text-left">Basic Info</h5>
                                    <form role="form" method="POST" action="{{ route('register-post') }}">
                                        {{ csrf_field() }}                                   
                                        <div class="form-group">
                                            <label for="first_name" class="control-label required">First Name</label>

                                            <div class="{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                                <input id="first_name" type="text" class="form-control" name="first_name" value="@if(!empty(old('first_name'))){!!old('first_name')!!}@else{!!$all_details['first_name'] or ''!!}@endif" required autofocus>

                                                @if ($errors->has('first_name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('first_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="surname" class="control-label required">Surname</label>

                                            <div class="{{ $errors->has('surname') ? ' has-error' : '' }}">
                                                <input id="surname" type="text" class="form-control" name="surname" value="@if(!empty(old('surname'))){!!old('surname')!!}@else{!!$all_details['surname'] or ''!!}@endif" required autofocus>

                                                @if ($errors->has('surname'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('surname') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="date_of_birth" class="control-label required">Date of birth</label>

                                            <div class="{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
                                                <div class="input-group input-append date" id="date_of_birth_cont">
                                                    <input id="date_of_birth" type="text" class="form-control" name="date_of_birth" value="@if(!empty(old('date_of_birth'))){!!old('date_of_birth')!!}@else{!!$all_details['date_of_birth'] or ''!!}@endif" required><span class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                                @if ($errors->has('date_of_birth'))
                                                    <div>
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('date_of_birth') }}</strong>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <h5 class="text-left">Address</h5>
                                        <div class="form-group">
                                            @if(false)
                                            <label for="unit_number" class="control-label">Unit No.</label>

                                            <div class="{{ $errors->has('unit_number') ? ' has-error' : '' }}">
                                                <input id="unit_number" type="text" class="form-control" name="unit_number" value="@if(!empty(old('unit_number'))){!!old('unit_number')!!}@else{!!$all_details['unit_number'] or ''!!}@endif" autofocus>

                                                @if ($errors->has('unit_number'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('unit_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <label for="street_number" class="control-label required">Street No.</label>

                                            <div class="{{ $errors->has('street_number') ? ' has-error' : '' }}">
                                                <input id="street_number" type="text" class="form-control" name="street_number" value="@if(!empty(old('street_number'))){!!old('street_number')!!}@else{!!$all_details['street_number'] or ''!!}@endif" autofocus required>

                                                @if ($errors->has('street_number'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('street_number') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <label for="street_name" class="control-label required">Street Name.</label>

                                            <div class="{{ $errors->has('street_name') ? ' has-error' : '' }}">
                                                <input id="street_name" type="text" class="form-control" name="street_name" value="@if(!empty(old('street_name'))){!!old('street_name')!!}@else{!!$all_details['street_name'] or ''!!}@endif" required autofocus>

                                                @if ($errors->has('street_name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('street_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @endif

                                            <div class=''>
                                                <label for="address_line_1" class="control-label required">Address line 1:</label>
                                                <div class="{{ $errors->has('address_line_1') ? ' has-error' : '' }}">
                                                    <input id="address_line_1" type="text" class="form-control" name="address_line_1" value="@if(!empty(old('address_line_1'))){!!old('address_line_1')!!}@else{!!$all_details['address_line_1'] or ''!!}@endif" required autofocus>
                                                    @if ($errors->has('address_line_1'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('address_line_1') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class=''>
                                                <label for="address_line_2" class="control-label">Address line 2:</label>
                                                <div class="{{ $errors->has('address_line_2') ? ' has-error' : '' }}">
                                                    <input id="address_line_2" type="text" class="form-control" name="address_line_2" value="@if(!empty(old('address_line_2'))){!!old('address_line_2')!!}@else{!!$all_details['address_line_2'] or ''!!}@endif" autofocus>
                                                    @if ($errors->has('address_line_2'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('address_line_2') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <label for="town_or_suburb" class="control-label required">Town or Suburb</label>

                                            <div class="{{ $errors->has('town_or_suburb') ? ' has-error' : '' }}">
                                                <input id="town_or_suburb" type="text" class="form-control" name="town_or_suburb" value="@if(!empty(old('town_or_suburb'))){!!old('town_or_suburb')!!}@else{!!$all_details['town_or_suburb'] or ''!!}@endif" required autofocus>

                                                @if ($errors->has('town_or_suburb'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('town_or_suburb') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <label for="state" class="control-label required">State</label>

                                            <div class="{{ $errors->has('state') ? ' has-error' : '' }}">
                                                {{ Form::jInput('select', 'state', $state_options, (!empty(old('state'))?old('state'):array_key_exists('state',$all_details)?$all_details['state']:''), ['class'=>'form-control', 'id'=>'state', 'required'=>true, 'autofocus'=>true]) }}

                                                @if ($errors->has('state'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('state') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @if(false) // remove, as per Paul 03/01/2017
                                            <label for="city" class="control-label required">City</label>

                                            <div class="{{ $errors->has('city') ? ' has-error' : '' }}">
                                                <input id="city" type="text" class="form-control" name="city" value="@if(!empty(old('city'))){!!old('city')!!}@else{!!$all_details['city'] or ''!!}@endif" required autofocus>

                                                @if ($errors->has('city'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('city') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                        <div class="form-group">                                        
                                            <label for="post_code" class="control-label required">Post Code</label>

                                            <div class="{{ $errors->has('post_code') ? ' has-error' : '' }}">
                                                <input id="post_code" type="text" class="form-control" name="post_code" value="@if(!empty(old('post_code'))){!!old('post_code')!!}@else{!!$all_details['post_code'] or ''!!}@endif" required autofocus>

                                                @if ($errors->has('post_code'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('post_code') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <h5 class="text-left">Contact Details</h5> 
                                        <div class="form-group">
                                            <label for="email_address" class="control-label required">E-Mail Address</label>

                                            <div class="{{ $errors->has('email_address') ? ' has-error' : '' }}">
                                                <input class="form-control" value="@if(!empty(old('email_address'))){!!old('email_address')!!}@else{!!$all_details['email_address'] or ''!!}@endif" disabled required>

                                                @if ($errors->has('email_address'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email_address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">    
                                            <label for="mobile_phone_number" class="control-label">Mobile Number</label>

                                            <div class="{{ $errors->has('mobile_phone_number') ? ' has-error' : '' }}">
                                                <input id="mobile_phone_number" type="text" class="form-control" name="mobile_phone_number" value="@if(!empty(old('mobile_phone_number'))){!!old('mobile_phone_number')!!}@else{!!$all_details['mobile_phone_number'] or ''!!}@endif" autofocus>

                                                @if ($errors->has('mobile_phone_number'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('mobile_phone_number') }}</strong>
                                                    </span>
                                                @endif
                                                <div class="spacers-1">&nbsp;</div>

                                            </div>
                                        </div>

                                        @if (false)
                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label for="password" class="control-label">Password</label>

                                            <div class="col-md-6">
                                                <input id="password" type="password" class="form-control" name="password" required>

                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                            <label for="password-confirm" class="control-label">Confirm Password</label>

                                            <div class="col-md-6">
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                                @if ($errors->has('password_confirmation'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif     
                                        <div class="form-group">                                          
                                            <input type="checkbox" id="disclosure" class="register-checkbox" name="disclosure" @if(!empty(old('disclosure'))){!!'checked'!!}@else{!! $all_details['disclosure'] or '' !!} @endif required><label for="disclosure">&nbsp;&nbsp;&nbsp;Please read and confirm that you have read and agree to the product disclosure and disclaimer</label>                                                                                   
                                        </div> 
                                        <div class="form-group">                                       
                                            <ul class="list-inline">
                                                <li class="pull-left"><a role="button" href="{{ route('register-front') }}" class="btn btn-primary next-step">Back</a></li>
                                                <li class="pull-right"><button type="submit" class="btn btn-primary next-step">Continue</button></li>
                                            </ul>                                       
                                        </div>
                                    </form>                                                                                  
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
@endsection