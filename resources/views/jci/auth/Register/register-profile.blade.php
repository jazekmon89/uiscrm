@extends('jci.layouts.Frontend')

{{-- Document title not page title --}}
@title('Register')

{{-- Page title --}}
@page_title('JCI - Register')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')
@css('css/datepicker.min.css', 'datepicker', 'app')

{{-- Let Document know the js block we're trying to add --}}
{{--@js('js/app.js', 'appjs', 'app')--}}
@js('plugins/datepicker/bootstrap-datepicker.js', 'bootstrap-datepickerjs', 'app')

@jsblock("auth.Register.registerJS.dates", "datesScript")

@section('content') 
<div class="bem-form__container-md bem-page__container-white bem-form__container-rounded bem-container__center">
    <h5 class="bem-form__heading-text bem-text_center">New User - Creating New Account</h5> 
    <div class="box-body">
        <section>                
            <div class="wizard">
                <div class="wizard-inner">
                    <div class="connecting-line"></div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="disabled">
                            <a href="#step1">
                                <span class="round-tab">
                                    <i class="bem-step__icons glyphicon glyphicon-folder-open"></i>
                                </span>
                            </a>
                        </li>
                        <li role="presentation" class="active">
                            <a href="#step2">
                                <span class="round-tab">
                                    <i class="bem-step__icons glyphicon glyphicon-picture"></i>
                                </span>
                            </a>
                        </li>
                        <li role="presentation" class="disabled">
                            <a href="#complete">
                                <span class="round-tab">
                                    <i class="bem-step__icons glyphicon glyphicon-pencil"></i>
                                </span>
                            </a>
                        </li>
                        <li role="presentation" class="disabled">
                            <a href="#complete">
                                <span class="round-tab">
                                    <i class="bem-step__icons glyphicon glyphicon-ok"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" id="step3">
                        @include('flash::message')
                        <form role="form" method="POST" action="{{ route('register-profile-post') }}">
                            {{ csrf_field() }}
                            <h5 class="bem-text_left">Additional Information Required</h5>
                            <div class="form-group">
                                <label for="first_name" class="control-label required">First Name</label>
                                <div class="{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <input id="first_name" type="text" class="form-control" name="first_name" value="@if(!empty(old('first_name'))){!!old('first_name')!!}@else{!!$all_details['first_name'] or ''!!}@endif" required autofocus />
                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>    
                            <div class="form-group">
                                <label for="middle_name" class="control-label">Middle Name</label>
                                <div class="{{ $errors->has('middle_name') ? ' has-error' : '' }}">
                                    <input id="middle_name" type="text" class="form-control" name="middle_name" value="@if(!empty(old('middle_name'))){!!old('middle_name')!!}@else{!!$all_details['middle_name'] or ''!!}@endif" autofocus />
                                    @if ($errors->has('middle_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('middle_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>    
                            <div class="form-group">
                                <label for="surname" class="control-label required">Surname</label>
                                <div class="{{ $errors->has('surname') ? ' has-error' : '' }}">
                                    <input id="surname" type="text" class="form-control" name="surname" value="@if(!empty(old('surname'))){!!old('surname')!!}@else{!!$all_details['surname'] or ''!!}@endif" required autofocus />
                                    @if ($errors->has('surname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('surname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>   
                            <div class="form-group">
                                <label for="preferred_name" class="control-label">Preferred Name</label>
                                <div class="{{ $errors->has('preferred_name') ? ' has-error' : '' }}">
                                    <input id="preferred_name" type="text" class="form-control" name="preferred_name" value="@if(!empty(old('preferred_name'))){!!old('preferred_name')!!}@elseif(empty($all_details['preferred_name'])){!!$all_details['first_name']!!}@else{!!$all_details['preferred_name'] or ''!!}@endif" autofocus />
                                    @if ($errors->has('preferred_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('preferred_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="date_of_birth" class="control-label required">Date of birth</label>
                                <div class="{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
                                    <div class="input-group input-append date" id="date_of_birth_cont">
                                        <input id="date_of_birth" type="text" class="form-control" name="date_of_birth" value="@if(!empty(old('date_of_birth'))){!!old('date_of_birth')!!}@else{!!$all_details['date_of_birth'] or ''!!}@endif" required /><span class="input-group-addon add-on"><i class="glyphicon glyphicon-calendar"></i></span>
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
                            <h5 class="bem-text_left">Place of Birth</h5>
                            <div class="form-group">
                                <label for="birth_country" class="control-label required">Country</label>
                                <div class="{{ $errors->has('birth_country') ? ' has-error' : '' }}">
                                    <input id="birth_country" type="text" class="form-control" name="birth_country" value="@if(!empty(old('birth_country'))){!!old('birth_country')!!}@else{!!$all_details['birth_country'] or ''!!}@endif" required autofocus />

                                    @if ($errors->has('birth_country'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('birth_country') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">       
                                <label for="birth_city" class="control-label required">Town/City</label>
                                <div class="{{ $errors->has('birth_city') ? ' has-error' : '' }}">
                                    <input id="birth_city" type="text" class="form-control" name="birth_city" value="@if(!empty(old('birth_city'))){!!old('birth_city')!!}@else{!!$all_details['birth_city'] or ''!!}@endif" required autofocus />
                                    @if ($errors->has('birth_city'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('birth_city') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">                                                                  
                                <div class="pull-left"><a role="button" href="{{ route('register') }}" class="btn btn-maroon next-step">Back</a></div>
                                <div class="pull-right"><button type="submit" class="btn btn-maroon next-step">Continue</button></div>                               
                            </div>
                            <div class="clearfix">&nbsp;</div>                            
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>     
@endsection
<style type="text/css">
    .bem-page__heading-text {
        display: none;
    }  
    .bem-footer {
        margin-top: 30px !important;
        position: relative !important;
    }
    @media (max-width: 768px) {
        .bem-member-page__heading-container {
            margin: 75px 0 15px !important;
        }
    } 
</style>