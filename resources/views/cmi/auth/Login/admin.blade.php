@extends('layouts.master-welcome-cmi')

{{-- Document title not page title --}}
@title('Sign in')

{{-- Page title --}}
@page_title('CMI Data - Sign in')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')

@section('body')
        <div class="bem-members__logo-container">
            <a href="/" title="CMI Data">
                <img src="/images/logo-cmi.png">
            </a>
        </div>
        <div class="row form-center">
            <div class="col-md-12">
                <div class="bem-members__container-main">
                    @include('flash::message')
                    <div class="bem-form__container-sm bem-page__container-white bem-form__container-rounded bem-container__center">
                        <h5 class="bem-text_center">Sign in to your session</h5>
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('admin-login-post') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }} has-feedback">
                                <div class="col-md-12">
                                    <input id="username" type="username" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required autofocus>
                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                   @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
                                <div class="col-md-12">
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Password"  required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                @if(false)
                                <div class="col-xs-8">
                                    <div class="checkbox">
                                      <label><input type="checkbox" value=""> Remember Me</label>
                                    </div>                                  
                                </div>
                                @endif
                                <!-- /.col -->
                                <div class="col-xs-4">
                                  <button type="submit" class="btn btn-primary btn-block btn-login">Sign In</button>
                                </div>
                                <!-- /.col -->
                            </div>                        
                        <div class="r-pad">&nbsp;</div>
                        </form>                            
                    </div>  
                    <p class="bem-text_right bem-member__links-sub-menu">
                        <a href="{{ route("quotes.request") }}" class="">Obtain a Quote</a> | 
                        <a href="{{ route("admin-login") }}" class="">Advisor Sign in</a> |  
                        <a href="{{ route("register-front") }}" class="">Register</a> | 
                        <a href="{{ route("inquiries.create") }}" class="">Submit an Inquiry</a>
                    </p>                     
                </div>               
            </div>    
        </div>       
@endsection

