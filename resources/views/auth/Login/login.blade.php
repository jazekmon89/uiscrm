@extends('layouts.master-welcome-cmi')

{{-- Document title not page title --}}
@title('Sign in')

{{-- Page title --}}
@page_title('UIS CRM - Sign in')

{{-- Document/Body title --}}
{{-- @body_class('rfq') --}}

{{-- Let Document know the css block we're trying to add --}}
@css('css/app.css', 'app')

{{-- Let Document know the js block we're trying to add --}}
@js('js/app.js', 'app')

@section('body')
        <div class="members-logo">
            <a href="/" title="CMI Data">
                <img src="/images/logo-cmi.png">
            </a>
        </div>
        <div class="row form-center">
            <div class="col-md-12">
                <div class="login-box">
                    @include('flash::message')

                    <div class="members-box-body">
                        <h5 class="text-center">Sign in to your session</h5>
                        <form class="form-horizontal" role="form">
                            {{ csrf_field() }}  
                            <div class="social-auth-links text-center">                                         
                                <a href="{{ route('googlelogin') }}" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i>Sign in with
            Google+</a>         
                                <div class="divider-or">
                                  <span class="text-or">or</span>
                                </div>                          
                                <a href="{{ route('facebooklogin') }}" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in with
                Facebook</a>                                              
                            </div>                                
                        </form>                               
                    </div>  
                     <p class="text-right members-text-links">
                        <a href="{{ route("quotes.request") }}" class="">Obtain a Quote</a> | 
                        <a href="{{ route("register-front") }}" class="">Register</a> | 
                        <a href="{{ route("inquiries.create") }}" class="">Submit an Inquiry</a>
                    </p>                     
                </div>               
            </div>    
        </div>
@endsection

